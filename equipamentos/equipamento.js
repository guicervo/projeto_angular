var equip = angular.module("equipamentos", []);
equip.directive('fileModel', ['$parse', function ($parse) {
    return {
    restrict: 'A',
    link: function(scope, element, attrs) {
        var model = $parse(attrs.fileModel);
        var modelSetter = model.assign;

        element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
        });
    }
   };
}]);

equip.service('fileUpload', ['$http', '$rootScope', function ($http, $rootScope) {
    this.uploadFileToUrl = function(file, uploadUrl, id_equipamento){
         var fd = new FormData();
         fd.append('file', file);
         fd.append('id_equipamento', id_equipamento);
         $http.post(uploadUrl, fd, {
             transformRequest: angular.identity,
             headers: {'Content-Type': undefined,'Process-Data': false}
         }).then(function(response){
         	if (response.data)
         	{
         		alert("Apenas arquivos JPEG e menor que 1MB são aceitos.");
         	}
         	angular.element("input[type='file']").val(null);
            $rootScope.$broadcast('eventFired', {
                data: id_equipamento
            });         		
            console.log("Success");
         }, function error(){
            console.log("Error");
         });
     }
 }]);

equip.controller("equipamentosCtrl", ['$scope', '$http', 'fileUpload', function($scope, $http, fileUpload){

	$scope.pagina = "Equipamentos";
	$scope.id_equipamento = "";
	$scope.clientes = [];
	$scope.equipamentos = [];
	$scope.funcionarios = [];
	$scope.eEquipamento = [];
	$scope.imagens = [];
	$scope.statusEquipamento = [{status : 'Aguardando Conserto'},
								{status : 'Em conserto'},
								{status : 'Consertado'},
								{status : 'Análise pelo Cliente'},
								{status : 'Sem conserto'}];

	$scope.retornoCliente = [{status : 'Aceito'}, {status : 'Recusado'}];
	
	// carrega itens
	var carregaClientes = function(){
		$http.get("../clientes/load.php").then(function (dados) {
			$scope.clientes = dados.data;
		}, function error(response){
			$scope.message_error = "Aconteceu um problema";
		});
	};

	var carregaFuncionarios = function(){
		$http.get("../funcionarios/load.php").then(function (dados) {
			$scope.funcionarios = dados.data;
		}, function error(response){
			$scope.message_error = "Aconteceu um problema";
		});
	};

	var carregaEquipamentos = function(){
		$http.get("load.php").then(function (dados) {
			$scope.equipamentos = dados.data;
		}, function error(response){
			$scope.message_error = "Aconteceu um problema";
		});
	};	

	//fim carrega itens

	$scope.adicionarEquipamento = function (dados){
		$http.post("insert.php", dados).then(function (){
			delete $scope.dados; // apaga do scope
			$scope.equipamentoForm.$setPristine(); // volta o form ao estado 'virgem'
			carregaEquipamentos();
			alert("Equipamento cadastrado com sucesso.");
		}, function error(response){
			$scope.message_error = "Erro ao inserir equipamento.";
		});
	};

	$scope.ordenarPor = function (campo){
		$scope.criterioDeOrdenacao = campo;
		$scope.direcaoOrdenacao = !$scope.direcaoOrdenacao;
	};	

	$scope.getEquipamentoById = function (idEquipamento){
		console.log(idEquipamento);
	};

	$scope.excluirEquipamento = function(id){
		$http.delete("delete.php", {params: {'idEquipamento' : id}}).then( function(){
			carregaEquipamentos();
		}, function error(response){
			$scope.message_error = "Erro ao excluir o serviço.";
		});		
	};

	$scope.getEquipamentoById = function (id){
		$http.get("retornaEquipamento.php", {params: {'idEquipamento' : id}}).then( function(dados){
			$scope.eEquipamento = dados.data;
		}, function error(response){
			$scope.message_error = "Erro ao buscar equipamento.";
		});
	};

	$scope.salvaEquipamento = function (dados){
		$http.post("salvaEquipamento.php", dados).then(function(response){
			alert(response);
			carregaEquipamentos();
		}, function error(response){
			$scope.message_error = "Erro ao editar equipamento.";
		});
	};

	$scope.salvaEnviaEquipamento = function (dados){
		dados.salvaEnvia = 1;
		$http.post("salvaEquipamento.php", dados).then(function(response){
			if (response.data)
			{
				alert("Notificação enviada.");
				carregaEquipamentos();
			}
			else
			{
				alert("Equipamento sem relatório do funcionário.");
				carregaEquipamentos();
			}
		}, function error(response){
			$scope.message_error = "Erro ao editar equipamento.";
		});
	};

	$scope.uploadFile = function(id_equipamento){
        var file = $scope.myFile;
        // console.log('file is ' );
        // console.dir(file);
        // console.log(id);
        var uploadUrl = "salvaImagens.php";
        fileUpload.uploadFileToUrl(file, uploadUrl, id_equipamento);
        $scope.myFile = null;
   	};

   $scope.getImagensByEquipamento = function (id){
		$http.get("loadImagens.php", {params: {'id_equipamento' : id}}).then(function (dados) {
			$scope.imagens = dados.data;
			$scope.id_equipamento = id;
		}, function error(response){
			$scope.message_error = "Aconteceu um problema";
		});
   };

   $scope.excluirImagem = function (id, id_equipamento){
   	console.log(id_equipamento);
   	$http.post("excluiImagem.php", id).then(function(response){
   		$scope.getImagensByEquipamento(id_equipamento);
   	});
   }

   	$scope.$on('eventFired', function(event, data) {
        $scope.getImagensByEquipamento(data.data);
    });

	//carrega metodos
	carregaEquipamentos();
	carregaClientes();
	carregaFuncionarios();
}]);