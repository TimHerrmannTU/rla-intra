$(function () {
	// State managment
	var super_filter = {
		"name": "standorte",
		"val": ""
	}
	var filters = {
		"standorte":  [],
		"fachgruppe":  [],
		"team": []
	}
	// Filterizr
	var filterContainer = $(".team-place")
	filterContainer.ready(function () {
		// Resize images
		const image_gap = 10;
		var image_count = 8
		if (screen.width < 1280) image_count = image_count/2 + 1
		const image_width = (screen.width-(image_gap*(image_count-1)))/image_count
		$(".item").each(function () {
			$(this).width(image_width)
		})
		// Initialize Filterizr
		console.log("Initializing Filterizr...")
		var fltr = filterContainer.filterizr({
			gridItemsSelector: ".item",
			layout: 'sameSize',
			gutterPixels: image_gap,
			setupControls: false
		})
		console.log("Success!")
		
		// Helper functions
		function fullReset() {
			super_filter["val"] = ""
			for (var key in filters) {
				filters[key] = []
			}
			$("#searchform-gallery input").val("")
			$("#searchform-gallery input").submit()
			$(".controls .active").removeClass("active")
		}

		// Setup super filter
		$("#"+super_filter["name"]).children("[control-type='filter']").each(function () {
			$(this).on("click", function () {
				var reset = $(this).hasClass("active")
				$("*[filter-parent]").hide() // hides all sub-filters
				fullReset()
				if (!reset) {
					super_filter["val"] = $(this).attr("filter")
					$("*[filter-parent="+super_filter["val"]+"]").show()
					$("*[filter="+super_filter["val"]+"]").addClass("active")
				}
				applyFilters()
			})
		})
		// Setup sub filter
		$("*[filter-target] button").on("click", function () {
			const target = $(this).parent().attr("filter-target")
			var reset = $(this).hasClass("active")
			var val = $(this).attr("filter")
			if (!reset) {
				filters[target].push(val)
				$(this).addClass("active")
			}
			else {
				filters[target].splice(filters[target].indexOf(val), 1)
				$(this).removeClass("active")
			}
			applyFilters()
		})
		// Reset button
		$("#filter-all").on("click", function () {
			fullReset()
			applyFilters()
		})
		// Function to apply filters based on active buttons
		function applyFilters() {
			$(".item").each(function () {
				var render = $(this).data("standorte").includes(super_filter["val"]) // is each sub-category returning true?
				if (render) {
					for (const key in filters) {
						const sub_filters = filters[key]
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
				}
				if (render) $(this).attr("data-category", "show")
				else $(this).attr("data-category", "hide")
			})
			fltr.filterizr("filter", "all")
			fltr.filterizr("filter", "show")
			console.log(filters)
		}
		// search bar
		$("#team-search").on("keyup", function() {
			var input_val = $(this).val().toLowerCase();
			fltr.filterizr('search', input_val)
		})
		
		// SORTING
		// Dropdown
		$("#sort-items").on("click", function () {
			$(this).toggleClass('active')
			$(this).closest(".dd-wrapper").find(".dd").toggleClass('hidden')
		})
		// Filter buttons
		const attributes = [ "index", "name"] // "index" is initial sorting (location -> ABC)
		attributes.forEach(attribute => {
			$("#sort-"+attribute).on("click", function () {
				var was_active = $(this).hasClass("active")
				$(this).closest(".dd").find(".active").removeClass("active")
				if (attribute == "random") fltr.filterizr("shuffle");
				else {
					if (was_active) {
						fltr.filterizr('sort', 'index', 'asc')
					}
					else {
						fltr.filterizr('sort', attribute, "asc")
						$(this).addClass("active")
					}
				}
				$("#sort-items").trigger("click")
			})
		});

		// bonus info
		$(".item .trigger").on("click", function (event) {
			event.stopPropagation()
			$(this).parent().find(".mehr-info").toggle()
		})
	})

})
