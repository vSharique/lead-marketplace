/**
 * Created by ananth on 7/1/17.
 */
(function(angular) {
    'use strict';
    angular.module('learnAngular', [])
        .controller('postExample', ['$scope', function($scope) {
            $scope.post = {
                title: 'Enter Title'
            };

            $scope.submit = function(){
                alert( 'save');
            }
        }]).controller('postsExample', ['$scope', function($scope) {
        $scope.posts = [
            { title: 'Post One' },
            { title: 'Post Two' }
        ];

    }]);
})(window.angular);