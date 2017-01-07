/**
 * Created by ananth on 7/1/17.
 */
(function(angular) {
    'use strict';
    angular.module('leadPortalModule', [])
        .controller('leadsFromAPI', ['$scope', function($scope) {
        $scope.cards = [
            { Name: 'Rohit', Location: 'Lucknow', Category: 'CEO', Query: 'Nirvana'},
            { Name: 'Anantharam', Location: 'Chennai', Category: 'CTO', Query: 'Relationship' }
        ];
    }]);
})(window.angular);