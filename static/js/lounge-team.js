jQuery(document).ready(function($) {
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
	var parent = $("#team")
	parent.ready(function () {
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
		var all_filters = []
		$("#standorte button").each(function() {
			var team = $(".teams-"+$(this).text())
			try {
				var temp = team.filterizr({
					gridItemsSelector: ".item",
					layout: 'sameSize',
					gutterPixels: image_gap,
					setupControls: false
				})
				all_filters.push(temp)
			}
			catch (e) {}
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
			filter("filter", "all")
			filter("filter", "show")
		}
		// Filter all sub-containers at once
		function filter(type, target) {
			all_filters.forEach((e) => {
				e.filterizr(type, target)
			})
			hideEmptyPlaces()
		}
		// hide empty places
		function hideEmptyPlaces() {
			$(".teams-wrapper").each(function(x, place) {
				var empty = true;
				$(place).find(".item").each(function(y, item) {
					if ($(item).attr("data-category") == "show") empty = false;
				})
				empty ? $(place).hide() : $(place).show()
			})
		}
		// search bar
		$("#team-search").on("keyup", function() {
			var input_val = $(this).val().toLowerCase();
			filter('search', input_val)
		})
	})

})
