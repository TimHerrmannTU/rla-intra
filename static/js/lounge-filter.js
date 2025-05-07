jQuery(document).ready(function($) {
	// (type1 | ... | typeN) & ... & (ratio1 | ... | ratioN)
	// "colors": {"multi":true,  "vals":[]},
	var filters = {
		"types":  {"multi":false, "vals":[]},
		"perspectives":   {"multi":true,  "vals":[]},
		"ratios-Lageplan": {"multi":true, "vals":[]},
		"ratios-Detail": {"multi":true, "vals":[]},
		"ratios-Schnitt": {"multi":true, "vals":[]},
		"tags":   {"multi":true,  "vals":[]},
		"formats":   {"multi":false,  "vals":[]},
		"styles":   {"multi":true,  "vals":[]},
		"lands":   {"multi":true,  "vals":[]}
	}

	// dd state
	var dd_states = {
		"ratios": {"vis": false, "open": false},
		"perspectives": {"vis": false, "open": false},
		"ratios-Lageplan": {"vis": false, "open": false},
		"ratios-Detail": {"vis": false, "open": false},
		"ratios-Schnitt": {"vis": false, "open": false},
		"tags": {"vis": true, "open": false},
		"formats": {"vis": false, "open": false},
		"styles": {"vis": false, "open": false},
		"lands": {"vis": true, "open": false},
	}
	const types_with_ratios = ["Lageplan", "Detail", "Schnitt"]

	$("#gallery .item").each(function () {
		var $img = $(this).find("img")
		$img.attr("src", $(this).data("thumb"))
		if ($(this).data("thumb") == $(this).attr("href")) {
			console.log("No Thumbnail present: ", $(this).attr("f-project"), $(this).data("thumb"))
		}
	})

	var filterContainer = $("#gallery")
	if (filterContainer.length > 0) {
		console.log("Target container found!")
	}
	filterContainer.ready(function () {
		// Initialize Filterizr
		console.log('Initializing Filterizr', $(filterContainer).length, $(filterContainer).find("a.item").length);
		filterContainer.filterizr({
			gridItemsSelector: "a.item",
			layout: 'packed',
			gutterPixels: 30
		})
		console.log("Initializing finished")

		// load images lazy
		window.dispatchEvent(new Event('resize')); // weird work around to keep the layout working
		$("#control-panel").show(); // only show the lounge after successfull init
		$("#lounge").show(); // only show the lounge after successfull init
		filterContainer.filterizr("shuffle");

		///////////////////////////
		// State management code //
		//////////////////////////

		// filtering options
		function setFilters(key, val, active) {
			if (active) {
				if (filters[key]["multi"]) {
					filters[key]["vals"].push(val)
				}
				else { // radio button
					filters[key]["vals"] = [val]
					$("#"+key+" > button").removeClass("active")
					$("#filter-"+val).addClass("active")
				}
			}
			else {
				filters[key]["vals"].splice(filters[key]["vals"].indexOf(val), 1)
			}
		}
		function resetFilters() {
			for (var key in filters) {
				filters[key]["vals"] = []
			}
			$(".icon-down-open-2").removeClass("hidden")
			$(".active").removeClass("active")
			sbs = ["perspectives", "ratios"]
			sbs.forEach(sb => {
				$("#show-"+sb).addClass("hidden")
			})
			$("#searchform-gallery input").val("")
			$("#searchform-gallery input").submit()
		}
		function resetSubFilters() {
			var not_type_filters = ["perspectives", "ratios-Lageplan", "ratios-Detail", "ratios-Schnitt", "tags", "formats", "styles"]
			not_type_filters.forEach(ntf => {
				filters[ntf]["vals"] = []
			})
		}
		function updateDDs() {
			for (const [key, val] of Object.entries(dd_states)) {
				if (val["vis"]) $("#show-"+key).show()
				else {
					$("#show-"+key).hide()
					$("#"+key+" .active").removeClass("active")
				}
				if (val["open"]){
					$("#"+key).show()
					$("#show-"+key+" .icon-down-open-2").addClass("up")
				}
				else {
					$("#"+key).hide()
					$("#show-"+key+" .icon-down-open-2").removeClass("up")
				}
			}
		}
		updateDDs()
		function resetDDs() {
			for (var [key, val] of Object.entries(dd_states)) {
				if (key != "tags" && key != "lands") {
					dd_states[key]["vis"] = false
					dd_states[key]["open"] = false
				}
			}
			updateDDs()
		}

		/////////
		// GUI //
		/////////

		// Reset button
		$("#filter-all").on("click", function () {
			filterContainer.filterizr("filter", "all")
			dd_states["tags"]["open"] = false
			resetDDs()
			resetFilters()
		})
		// Type filters
		$("#types").children("[control-type='filter']").each(function () {
			$(this).on("click", function () {
				// reset GUI
				$(this).closest("[control-type='group']").find("button.active").removeClass("active")
				// adjust GUI based on filter state
				var activated_filter = $(this).attr("filter")
				var reset = (filters["types"]["vals"][0] == activated_filter) ? true : false;
				resetSubFilters()
				// reset <=> active type button gets clicked again
				if (!reset) {
					filters["types"]["vals"] = [activated_filter]
					resetDDs()
					$(this).addClass("active")
					if (activated_filter == "Perspektive") {
						dd_states["styles"] = {"vis": true, "open": false}
						dd_states["perspectives"] = {"vis": true, "open": false}
					}
					if (activated_filter == "Layout") {
						dd_states["formats"] = {"vis": true, "open": false}
					}
					if (types_with_ratios.includes(activated_filter)) {
						dd_states["styles"] = {"vis": true, "open": false}
						dd_states["ratios"] = {"vis": true, "open": false}
						dd_states["ratios-"+activated_filter] = {"vis": true, "open": false}
					}
					updateDDs();
				}
				else {
					filters["types"]["vals"] = []
					resetDDs()
				}
				applyFilters();
			})
		})
		// dd content buttons
		$("[control-type='sub']").children("[control-type='filter']").each(function () {
			$(this).on("click", function () {
				$(this).toggleClass("active")
				var active = $(this).hasClass("active")
				setFilters($(this).closest("[control-type='sub']").attr("id"), $(this).attr("filter"), active)
				applyFilters();
			})
		})
		// dd buttons toggles
		var single_sub_cats = ["tags", "perspectives", "styles", "formats", "lands"]
		single_sub_cats.forEach(ssc => {
			$("#show-"+ssc).on("click", function () {
				dd_states[ssc]["open"] = !dd_states[ssc]["open"]
				updateDDs()
			})
		})
		$("#show-ratios").on("click", function() {
			dd_states["ratios"]["open"] = !dd_states["ratios"]["open"]
			types_with_ratios.forEach(twr => {
				var dd_state = dd_states["ratios-"+twr]
				if (dd_state["vis"]) dd_state["open"] = !dd_state["open"]
			})
			updateDDs()
		})
		// Function to apply filters based on active buttons
		function applyFilters() {
			$(".item").each(function () {
				var render = true // is each sub-category returning true?
				for (const key in filters) {
					const sub_filters = filters[key]["vals"]
					if (sub_filters.length > 0) {
						var any_match = false // is any of the relevant values present?
						sub_filters.forEach(val => {
							if ($(this).attr("f-"+key).includes(val)) {
								any_match = true
							}
						})
						if (!any_match) {
							render = false
							break
						}
					}
				}
				if (render) $(this).attr("data-category", "show")
				else $(this).attr("data-category", "hide")
			})
			filterContainer.filterizr("filter", "all")
			filterContainer.filterizr("filter", "show")
			console.log(filterContainer)
		}
		// Misc options
		$("#show-info").on("click", function () {
			$(".image-mask").toggleClass("active")
			$(this).toggleClass("active")
		})
		// Sorting buttons
		const attributes = [ "date", "place", "random", "title"] // "color" needs to be added again if wanted
		attributes.forEach(attribute => {
			$("#sort-"+attribute).on("click", function () {
				var was_active = $(this).hasClass("active")
				$(this).closest(".dd").find(".active").removeClass("active")
				if (attribute == "random") filterContainer.filterizr("shuffle");
				else {
					if (was_active) {
						filterContainer.filterizr('sort', 'index', 'asc')
					}
					else {
						filterContainer.filterizr('sort', attribute, "asc")
						$(this).addClass("active")
					}
				}
				$("#sort-items").trigger("click")
			})
		});

		$("#lounge-search").on("keyup", function() {
			var input_val = $(this).val().toLowerCase();
			filterContainer.filterizr('search', input_val)
		})
	})
	// Sorting pop up
	$("[control-type='sort']").on("click", function () {
		$(this).toggleClass('active')
		$(this).closest(".dd-wrapper").find(".dd").toggleClass('hidden')
	})
})