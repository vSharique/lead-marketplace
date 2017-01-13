/**
 * Created by ananth on 7/1/17.
 */
(function (angular) {
	'use strict';
	angular.module('leadPortalModule', ['ngAnimate'])
		.controller('leadsFromAPI', ['$scope', '$http', function ($scope, $http) {
			$scope.cardSelectionCriteria = function(card){
				if(!$scope.user_hidden && card.isHidden) {
					return false;
				}
				if($scope.userSelectedLocations!=0 && !($scope.containsInArray($scope.userSelectedLocations,card.Location))) {
					return false;
				}
				if($scope.userSelectedCategories!=0 && !($scope.containsInArray($scope.userSelectedCategories,card.Category))) {
					return false;
				}
				return true;
			};
			$scope.containsInArray = function(a, obj) {
				for (var i = 0; i < a.length; i++) {
					if (a[i] === obj) {
						return true;
					}
				}
				return false;
			}
			$scope.userSelectedLocations = [];
			$scope.userSelectedCategories = [];
			$scope.setSelectedCategories = function(prop){
				if(!($scope.containsInArray($scope.userSelectedCategories,prop.Name))) {
					$scope.userSelectedCategories.push(prop.Name);
				}else{
					removeItemFromArray($scope.userSelectedCategories,prop.Name);
				}
			};
			$scope.setSelectedLocations = function(prop){
				if(!($scope.containsInArray($scope.userSelectedLocations,prop.Name))) {
					$scope.userSelectedLocations.push(prop.Name);
				}else {
					removeItemFromArray($scope.userSelectedLocations,prop.Name);
				}
			};
			$scope.toggle_card_hidden = function (card) {
				card.isHidden = !card.isHidden;
			};
			$scope.cards = [];
			$scope.topLocations = [];
			$scope.topCategories = [];
			$http({
				url: '/wp-json/marketplace/v1/leads/details',
				cache: true
			})
				.success(function (data, status, headers, config) {
					// this callback will be called asynchronously
					// when the response is available
					var locationCount = {};
					var categoryCount = {};
					for (var index = 0; index < data.length; ++index) {
						var card = data[index].lead_card;
						var current_card = {
							Name: card.name,
							Location: card.location,
							Category: card.category,
							Query: card.query,
							isHidden: card.isHidden
						};
						var locationInt = ++locationCount[card.location];
						var catrgoryInt = ++categoryCount[card.category];
						if(isNaN(locationInt)) {
							locationCount[card.location] = locationInt = 1;
						}
						if(isNaN(catrgoryInt)) {
							categoryCount[card.category] = catrgoryInt = 1;
						}
						var currentLocation = {
							Name: card.location,
							Count:locationInt
						};
						var currentCategory = {
							Name: card.category,
							Count:catrgoryInt
						};
						$scope.cards.push(current_card);
						if(!(currentLocation in $scope.topLocations)) {
							$scope.topLocations.push(currentLocation);
						}
						if(!(currentCategory in $scope.topCategories)) {
							$scope.topCategories.push(currentCategory);
						}
					}
				})
				.error(function (data, status, header, config) {
					// called asynchronously if an error occurs
					// or server returns response with an error status.
					alert("Unable to fetch the lead details from the API.");
				});
		}]);
})(window.angular);

/* Some unknown function from Mayank*/

(function() {

  'use strict';

  var items = document.querySelectorAll(".timeline li");


  function isElementInViewport(el) {
    var rect = el.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  function callbackFunc() {
    for (var i = 0; i < items.length; i++) {
      if (isElementInViewport(items[i])) {
        items[i].classList.add("in-view");
      }
    }
  }

  // listen for events
  window.addEventListener("load", callbackFunc);
  window.addEventListener("resize", callbackFunc);
  window.addEventListener("scroll", callbackFunc);

})();

/**
 * @param {Array} array the original array with all items
 * @param {any} item the time you want to remove
 * @returns {Array} a new Array without the item
 */
var removeItemFromArray = function(arr, item){
	var i = arr.length;
	while( i-- ) if(arr[i] === item ) arr.splice(i,1);
}