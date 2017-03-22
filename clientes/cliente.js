angular.module("clientes", ["ngMessages"]);
angular.module("clientes").controller("clientesCtrl", function($scope, $http){
	$scope.pagina = "Clientes";

	$scope.clientes = []; 
	$scope.eCliente = [];


	$scope.ordenarPor = function (campo){
		$scope.criterioDeOrdenacao = campo;
		$scope.direcaoOrdenacao = !$scope.direcaoOrdenacao;
	};

	var carregarClientes = function () {
		$http.get("load.php").then(function (dados) {
			$scope.clientes = dados.data;
		}, function error(response){
			$scope.message_error = "Aconteceu um problema";
		});
	};

	$scope.adicionarCliente = function (cliente) { // adicionar ao DB
		$http.post("insert.php", cliente).then(function (){
			delete $scope.cliente; // apaga do scope
			$scope.clienteForm.$setPristine(); // volta o form ao estado 'virgem'
			carregarClientes();
		}, function error(response){
			$scope.message_error = "Erro ao inserir cliente.";
		});
	};				   

	$scope.excluirCliente = function (id) {
		$http.delete("delete.php", {params: {'idUser' : id}}).then( function(){
			carregarClientes();
		}, function error(response){
			$scope.message_error = "Erro ao excluir o cliente.";
		});
	}; 

	$scope.getClienteById = function (id){
		$http.get("retornaCliente.php", {params: {'idUser' : id}}).then( function(dados){
			$scope.eCliente = dados.data;
		}, function error(response){
			$scope.message_error = "Erro ao buscar cliente.";
		});
	};

	$scope.salvarCliente = function (editarCliente) {
		$http.post("salvaCliente.php", editarCliente).then(function (){
			delete $scope.editarCliente;
			$scope.editarClienteForm.$setPristine();
			carregarClientes();
		}, function error(response){
			$scope.message_error = "Erro ao editar o cliente.";
		});		
	};

	carregarClientes();
});

