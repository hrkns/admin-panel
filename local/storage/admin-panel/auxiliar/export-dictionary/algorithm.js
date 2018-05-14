angular.module('dictionary', [])
.run([
	function(){
	}
])
.controller('dict', [
    '$scope',
    function($scope){
    	if(window.localStorage["admin-panel-offline-edition"]){
    		$scope.dictionary = JSON.parse(window.localStorage["admin-panel-offline-edition"]);
    	}else{
	    	$scope.dictionary = Terms;
    	}

    	$scope.offline_dict = Terms.offline_dict;
    	$scope.SELECTED_LANGUAGE = "es";

    	$scope.toggle_terms = function(t){
    		t.show_terms = !t.show_terms;
    	}

    	$scope.dump_terms = function(){
    		$("#dumped_dictionary").val(JSON.stringify($scope.dictionary));
    		$("#modal_dumped_dictionary").modal("show");
    	}

    	$scope.change_language = function(){
    		$scope.SELECTED_LANGUAGE = $scope.select_language.code;
    	}

        $scope.reset = function(){
            delete window.localStorage["admin-panel-offline-edition"];
            window.location.reload();
        }

    	setInterval(function(){
    		window.localStorage["admin-panel-offline-edition"] = JSON.stringify($scope.dictionary);
    	}, 2000);
    }
]);