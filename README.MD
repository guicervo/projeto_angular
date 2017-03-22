Guia de instalação

O projeto foi desenvolvido utilizando a ferramenta XAMPP.
Para o front-end utilizou-se bootstrap com angularjs. 
Para o back-end utilizou-se do banco de dados mysql e para fornecer a conexão, PHP PDO. 
Para o envio do e-mail utilizou-se da biblicote PHPMailer.

Para o funcionamento do projeto, após a instalação do XAMPP, cria-se uma pasta dentro do diretório C:\xampp\htdocs\"nome-pasta" e em seguida, coloca-se o conteúdo dentro da pasta. 

O segundo passo é fazer o upload do banco de dados. Para isso, acesse http://localhost/phpmyadmin/ , cria um novo banco de dados com um nome qualquer, selecione o banco criado e, na aba IMPORTAR, seleciona o backup db_controle_equipamento.sql. 

Com o ambiente pronto, acesse http://localhost/"nome-pasta"

Projeto consiste em duas etapas, cadastro de clientes e equipamentos. 

Para cadastrar um equipamento, é obrigatório o cadastro de ao menos 1 cliente. 

	1. O cadastro do equipamento possue todos os campos obrigatórios, exceto pelo 'select' 'Selecione um funcionario'
		1.1 Não foi dado como obrigatório, pois todos os funcionários podem estar ocupados. 
	2. O preenchimento da avaria, é feito quando selecionado o equipamento para edição. O mesmo serve para alterar o status em que o equipamento se encontra e a resposta do cliente.
	3. A notificação só será enviada se o campo da avaria estiver completado. 
	4. Quando excluído um equipamento, todas informações relacionadas a ele também serão. O mesmo serve para o cliente. 
	5. Para o cadastro de imagens, clica-se no ícone de imagens.  
		5.1 É possível upar apenas imagens .jpeg e com tamanho menor que 1MB. 
		5.2 O upload é feito apenas de 1 em 1 foto. 


