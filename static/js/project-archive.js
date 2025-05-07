jQuery(document).ready(function($) {
	$("#header .search-wrapper").hide() // hides nav searchbar to avoid confusion
	$(".pro-table td[f-index='Fläche in ha'], .pro-table td[f-index='Bausumme gesamt']").each(function() {
		if ($(this).text().trim() == "") {
			$(this).addClass("afterless")
		}
	})
	// filter table rows by categories
	$(".cats-pro a").click(function() {
		var cat = $(this).attr("f-cat")
		if (cat == "Alle") {
			$(".cats-pro a").removeClass("active")
			$(this).addClass("active")
		}
		else {
			$(".cats-pro a[f-cat='Alle']").removeClass("active")
			$(this).toggleClass("active")
		}
		filter_cascade()
	})
	// reset search
	$("#pro-search").val("")
	// main filter function
	function filter_cascade() {
		console.log("Filtering...")
		var all_rows = $(".pro-table .pro-row");
		var relevant_rows = "";
		// lvl-1: cat filter
		if ($("#pro-controls [f-cat='Alle']").hasClass("active") || $("#pro-controls [f-cat].active").length == 0) {
			relevant_rows = all_rows
		}
		else {
			var cats = []
			$("#pro-controls [f-cat].active").each(function() {
				cats.push( ".pro-row[f-cat='"+$(this).attr("f-cat")+"']" )
			})
			relevant_rows = $(cats.join(", "))
		}
		// lvl-2: searchbar filter
		var input_val = $("#pro-search").val()
		if (input_val != "") {
			// splitting seperate inputs
			var input_list = input_val.split(",") // seperate multiple search inputs
			input_list = input_list.map(inp => inp.trim()) // remove usless whitespaces
			input_list = input_list.filter(inp => inp != "") // remove empty strings (will match anything)
			// filtering
			relevant_rows = relevant_rows.filter(function(x, row) {
				var render = false
				$(row).find("td:not([style*='display: none']), td[f-index='Projektkürzel'], td[f-index='Mitarbeiter']").each(function(y, cell) {
					input_list.forEach(inp => {
						// input sanitizing
						if(/[A-Z]{3}/.test(inp)) {
							if ($(cell).text().match(inp)) {
								render = true
							}
						}
						else { 
							inp = inp.toLowerCase()
							if ($(cell).text().toLowerCase().match(inp) || $(cell).text().match(inp)) {
								render = true
								// Break needed!
							}
						}
					})
				})
				return render
			})
			console.log("Filtered by search")
		}
		// lvl-3: country / places
		$("[f-radio]").each(function() {
			var col_index = $(this).attr("f-col")
			var active = $(this).find("button.active").map(function() {
				return $(this).text().trim()
			}).toArray()
			if (active.length > 0) {
				console.log(active.length)
				relevant_rows = relevant_rows.filter(function() {
					if (active.includes($(this).find("[f-index='"+col_index+"']").text().trim())) {return true}
					else {return false}
				})
				console.log("Filtered by location")
			}
		})
		var planed = $("#planned").is(":checked")
		// lvl-4: range filter (Fertigstellung)
		if (min != start || max != end || !planed) {
			relevant_rows = relevant_rows.filter(function() {
				var val = $(this).find("[f-index='Jahr Fertigstellung']").text().trim()
				if (val == "4in Planung" && planed) { // "4" is just there for sorting prio, this is propably a poor solution & should be fixed soon
					return true
				}
				else {
					val = parseInt(val)
					if (start <= val && val <= end) {
						return true
					}
				}
				return false
			})
		}
		// lvl-5: only show rows where col X is set
		var active = []
		$("[f-type='hide-row-if-col-empty'] input:checked").each(function() {
			active.push($(this).attr("f-col").trim())
		})
		if (active.length > 0) {
			active.forEach(a => {
				relevant_rows = relevant_rows.filter(function() {
					var val = $(this).find("[f-index='"+a+"']").text().trim()
					if (val != "") return true
					else return false
				})
			})
		}

		// render
		if (relevant_rows != all_rows) change_display(all_rows.not(relevant_rows), false)
		change_display(relevant_rows, true)
	}
	// change display & control attribute at the same time
	function change_display(e, show) {
		if (show) {
			$(e).show()
			$(e).attr("f-filtered", 1)
		}
		else {
			$(e).hide()
			$(e).attr("f-filtered", 0)
		}
	}
	$(".close-options").click(function() {
		$('.side-menu').toggleClass("no-overflow")
		$('#pro-controls').toggle()
		$("#options-trigger img").toggleClass("flip")
	})
	// create unique filter buttons for a column
	function createRadioForCol(columnID, breakpoint=0) {
		var counts = {};
		var results = $("td[f-index='"+columnID+"']").map(function() {
			return $(this).text().trim();
		}).toArray()
		results.forEach(function (x) { counts[x] = (counts[x] || 0) + 1; });
		console.log(counts)
		for (var result in counts) {
			if (counts[result] > breakpoint) {
				var btn = $(".templates .filter-button.bordered").clone().text(result);
				btn.click(function() {
					$(this).toggleClass("active");
					filter_cascade();
				})
				result != "" ? $("#pro-controls [f-col='"+columnID+"']").append($(btn)) : null;
			}
		}
	}
	createRadioForCol("Ort", 10)
	createRadioForCol("Land", 5)
	createRadioForCol("Art der Planung", 0)
	
	$("[f-type='hide-row-if-col-empty'] input").click(function() { 
		filter_cascade()
	})
	// fetching the oldest & newest project years
	var time_range = $("td[f-index='Jahr Fertigstellung']").map(function() {
		var year = parseInt($(this).text().trim());
		if (year && (year > 999)) {return year}
	})
	time_range = [...new Set(time_range)]
	// states
	var sliders = [ // CLASS???
		{"name": "", "min": 0, "max": 0, "unit": ""},
	]
	const slider_names = ["time", "area", "cash"]
    const min = time_range.pop()
    const max = time_range[0]
    var start = min
    var end = max

	// NEW ATTEMPT
	slider_names.forEach(slider_name => {
		var $slider = $("#range-slider-"+slider_name)
		var slider_min = parseInt($slider.attr("min")) 
		var slider_max = parseInt($slider.attr("max"))
		var slider_unit = $slider.attr("unit")
		$slider.find(".slider").slider({
			range: true,
			min: slider_min,
			max: slider_max,
			values: [slider_min, slider_max],
			slide: function(event, ui) {
				start = ui.values[0] // TODO 
				$slider.find(".from").val(start + slider_unit);
				end = ui.values[1] // TODO
				$slider.find( ".to" ).val(end + slider_unit);
				filter_cascade()
			}
		});
		$slider.find(".from").val($slider.find(".slider").slider("values", 0) + slider_unit)
		$slider.find( ".to" ).val($slider.find(".slider").slider("values", 1) + slider_unit)
	});
	$("#planned").click(function() { 
		filter_cascade()
	})
    function reset_range(target) {
        $("#"+target+" .from").val(min)
        $("#"+target+" .from").trigger("input")
        $("#"+target+" .to").val(max)
        $("#"+target+" .to").trigger("input")
    }

	// expand cats
	$("#show-cats").click(function() {
		$(this).find("span").toggleClass("up");
		$("#cats").toggle();
	})
	// searchbar
	$("#pro-search").on("keyup", function() {
		filter_cascade()
	})
	// toggle visibility of certain table columns
	$("#bonus-cols :not(#toggle-col-buttons)").click(function() {
		$(this).toggleClass("active")
		change_display($("[f-index='"+$(this).attr("f-target")+"']"), $(this).hasClass("active"))
	})
	var show_buttons = Boolean(true);
	$("#bonus-cols #toggle-col-buttons").click(function() {
		if (show_buttons) {
			$(this).find("svg").css("transform", "rotate(45deg)")
			$("#bonus-cols button").show()
		} 
		else {
			$(this).find("svg").css("transform", "rotate(0deg)")
			$("#bonus-cols button:not(.active, #toggle-col-buttons").hide()
		}
		show_buttons = !show_buttons
	})
	// auto select cols when user selects the "Dokublatt"-Export
	$("#export-settings input[name='export-type'][value='doku']").change(function() {
		if ($(this).is(":checked")) {
			var $col_buttons = $("#bonus-cols button.active:not(#toggle-col-buttons)")
			$col_buttons.click()
			$col_buttons.hide()
			$col_buttons = $("#bonus-cols button:not(#toggle-col-buttons)[f-prodo='1']")
			$col_buttons.click()
			$col_buttons.show()
		}
	})
	// redirect user to project entry
	$(".pro-table .pro-row").click(function() {
		window.open($(this).attr("href"), '_blank').focus();
	})
	// hide unwanted rows
	$(".hide-row").click(function(event) {
		event.stopPropagation()
		$(this).toggleClass("disabled")
		var row = $(this).closest("tr")
		row.attr("f-disabled", ((parseInt(row.attr("f-disabled"))+1) % 2))
	})

	// sorting the table by column by clicking in the table header
	$('.pro-table th').click(function(){
		// styling
		$('.pro-table th').not($(this)).attr("f-state", 0)
		var new_state = ($(this).attr("f-state") + 1) % 3
		if (new_state == 0) new_state = 1
		$(this).attr("f-state", new_state)
		// filtering
		var table = $(this).parents('table').eq(0)
		var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
		this.asc = !this.asc
		if (!this.asc){rows = rows.reverse()}
		for (var i = 0; i < rows.length; i++){table.append(rows[i])}
	})
	// filter function for the table sort
	function comparer(index) {
		return function(a, b) {
			var valA = getCellValue(a, index), valB = getCellValue(b, index)
			return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
		}
	}
	function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
	$(".pro-table th[f-index='Jahr Fertigstellung']").click()
	$(".pro-table th[f-index='Jahr Fertigstellung']").click()

	// reset page
	$(".reload").click(function() {
		location.reload();
	})
})