<?php 
require_once(dirname(dirname(__FILE__))."/funcoes.php");
protegeArquivo(basename(__FILE__));
loadJS('geral');
loadCSS('style');

 
?>

<?php 
switch ($tela){
	case 'login':?>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css"/>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="https://kit.fontawesome.com/6fa2c6bdb7.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
	<style type="text/css">
		body{
			background: #fff !important;
		}
		.login{
			padding: 70px 0;
		}
		.login .bg_form_login{
			background: #f8f8f8;
			padding: 20px;
			display: inline-block;
			border-radius: 15px;
		}
		.login h5{
			font-weight: 700;
		}
		.login .logo_login{
			margin-bottom: 15px;
		}
	</style>
	<?php 

	if (isset($_POST['entrar'])) {
		if (verificaDados(secureInput($_POST['login_usuario']), secureInput($_POST['senha_usuario']))) {
			redireciona('painel.php');
		}else{
			echo "Não foi possível fazer o login, tente novamente!";
		}

		if (isset($_POST['enviar'])) {
			if ($email = validar_email($_POST['email_usuario'])) {
				recuperarSenha();
			}
		}
	}
	?>


	<div class="row login">
		<div class="container center-align">
			<div class="col s12 logo_login">
				<img src="img/logostatus.png">
			</div>
			<div class="col s12 m3 l3"></div>
			<form class="col s12 m6 l6" action="" method="post">
				<div class="bg_form_login">
					<div class="input-field col s12">
						<label>Usuario</label>
						<input type="text" name="login_usuario">
					</div>
					<div class="input-field col s12">
						<label>Senha</label>
						<input type="password" name="senha_usuario">
					</div>
					<div class="input-field col s12">
						<input type="submit" name="entrar" class="waves-effect waves-light btn" value="Entrar">
					</div>
				</div>
				<a href="JavaScript:recuperarSenha();">Esqueceu a senha?</a>
			</form>
			<div class="col s12 m3 l3"></div>
		</div>
	</div>
		<div class="row">
	<div class="container">
			<div class="col s12 popup" id="telaRecuperarSenha">
				<form method="post">
					<p>Você receberá um e-mail com as instruções para alterar sua senha.</p>
					<div class="input-field col s12">
						<label>Digite seu e-mail!</label>
						<input type="text" name="email_usuario" >
					</div>
					<div class="input-field col s12">
						<input type="submit" name="enviar" class="waves-effect waves-light btn" value="Enviar">
					</div>
				</form>
					<div class="row"></div>
			</div>
		</div>
	</div>

	<?php 
	break;

	//CADASTRAR NOVO USUARIO
	case 'incluir':
	?>
 <script>
        function validarFormulario() {
            var senha = document.getElementById('senha').value;
            var confirmacaoSenha = document.getElementById('confirmacaoSenha').value;

            if (senha !== confirmacaoSenha) {
                alert('As senhas não coincidem. Por favor, tente novamente.');
                return false; // Impede o envio do formulário
            }

            // Outras validações de segurança podem ser adicionadas aqui

            return true; // Permite o envio do formulário
        }
    </script>
	
	<?php 
	    
	    
	    $duplicado = false;
        if (isset($_POST['publicar'])){
		$user = new usuarios(array(
			'nome_usuario'=>$_POST['nome'],
			'email_usuario'=>$_POST['email'],
			'login_usuario'=>$_POST['login'],
			'senha_usuario'=>hash_password($_POST['password']),
			'nivel_usuario_id_nivel_usuario '=>1,
		
		));

		if ($user->existeRegistro('email_usuario', $_POST['email'])){
		//	popup(null, null, 'Usuário ja cadastrado!', 'aviso');
			$duplicado = true;
		}
		if (isset($duplicado) and $duplicado!=true){
			$user->inserir($user);
			if($user->linhasafetadas > 0){
				$lastid = mysqli_insert_id($user->conexao);

				
				header("Location: ?m=usuarios&t=editar&id=".$lastid."&status=save");
			}
		}
	}
	?>
	
	


	<div class="row">
		<form class="col s12 bg_fff" id="formUser" action="" onsubmit="return validarFormulario()"   method="post" enctype="multipart/form-data">
			<div class="col s9 border-right" >
				<h5>Usuários</h5>
				<div class="col s12 input-field">
					<input type="text" id="nome" name="nome">
					<label for="nome">Nome</label>
				</div>
				<div class="col s12 input-field">
					<input type="text" id="email" name="email">
					<label for="email">E-mail</label>
				</div>
				<div class="col s12 input-field">
					<input type="text" id="login" name="login">
					<label for="login">Nome Usuário</label>
				</div>
				<div class="col s6 input-field">
    				<select class="select2" name="nivel_usuario_id_nivel_usuario " id="nivel_usuario_id_nivel_usuario ">
    					<option value="1">Administrador</option>
    					<option value="2" selected>Instrutor</option>
    				</select>
				</div>
				<div class="col s12 input-field">
					<input type="password" id="senha" name="password">
					<label for="password">Senha</label>
				</div>
				<div class="col s12 input-field">
					<input type="password" id="confirmacaoSenha" name="password_again">
					<label for="password_again">Confirmar Senha</label>
				</div>
				
				
			</div>
			<div class="col s3">
				<div class="col s12  input-field">
					<input type="submit" name="publicar" value="Publicar" class="btn bg_color" >
				</div>
			</div>
		</form>

	</div>
	<?php 


	break;

	//LISTAR
	case 'listar':
	?>
	<div class="row">
		<div class="col s12 ">

		</div>
		<div class="col s12 bg_fff">
			<div class="col s6">
				<h5>Todas os Usuários</h5>
			</div>
			<div class="col s6 right-align">
				<a href="<?= BASEURL  ?>painel.php?m=usuarios&t=incluir">
					<div class="bt-new ">
						<i class="fas fa-plus"></i> Novo Usuário
					</div>
				</a>
			</div>
			<div class="col s12">
				<table class="responsive-table striped highlight">
					<thead>
						<tr>
							<th>Nome</th>
							<th>E-mail</th>
							<th>Login</th>
							<th class="center-align">Editar/Excluir</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$user = new usuarios();
						$user->extras_select = "where status = 1";
						$user->selecionaTudo($user);
						while ($res = $user->retornaDados()){
							?>
							<tr>
								<td><?= $res->nome_usuario ?></td>
								<td><?= $res->email_usuario ?></td>
								<td><?= $res->login_usuario ?></td>
								<td class="center-align">
									<a href="<?= BASEURL ?>painel.php?m=usuarios&t=editar&id=<?= $res->id_usuario ?>" class="btn bg_color">
										<i class="far fa-edit"></i>
									</a>
								</td>
							</tr>
							<?php 
						}

						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php 

	break;

	//EDITAR USUARIO
	case 'editar':

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		

		



			if (isset($_POST['atualizar'])) {
				$usuarios = new usuarios(array(
					"nome_usuario" => $_POST['nome'],
					"email_usuario" => $_POST['email'],
					"login_usuario" => $_POST['login'],
				));
				$usuarios->valorpk=$id;
				$usuarios->atualizar($usuarios);


			
				header("Location: ?m=usuarios&t=editar&id=".$id."&status=update");

			}
		//dados do banco
			$usuarios = new usuarios();
			$usuarios->extras_select = "where id_usuario = $id";
			$usuarios->selecionaTudo($usuarios);
			$res = $usuarios->retornaDados();
			?>
			<div class="row">
				<form class="col s12 bg_fff" id="formUser" action=""   method="post" enctype="multipart/form-data">
					<?php 
					if ($_GET['status']=='save') { 
						?>
						<script type="text/javascript">
							saveOk();
						</script>
						<div id="aviso" style="opacity: 0;" >
							Publicado com sucesso!
						</div>
						<?php 
					}
					if ($_GET['status']=='update') { 
						?>
						<script type="text/javascript">
							saveOk();
						</script>
						<div id="aviso" style="opacity: 0;" >
							Atualizado com sucesso!
						</div>
						<?php 
					}
					if ($_GET['status']=='updatesenha') { 
						?>
						<script type="text/javascript">
							saveOk();
						</script>
						<div id="aviso" style="opacity: 0;" >
							Senha atualizada com sucesso!
						</div>
						<?php 
					}
					if ($_GET['status']=='erro') { 
						?>
						<script type="text/javascript">
							saveOk();
						</script>
						<div id="aviso" style="opacity: 0;" >
							Não foi possível gravar as alterações!
						</div>
						<?php 
					}
					?>
					<div class="col s12 m12 l9 border-right" >

						<h5>Usuários</h5>
						<div class="col s12 input-field">
							<input type="text" id="nome" name="nome" value="<?= $res->nome_usuario; ?>">
							<label for="nome">Nome</label>
						</div>
						<div class="col s12 input-field">
							<input type="text" id="email" name="email" value="<?= $res->email_usuario; ?>">
							<label for="email">E-mail</label>
						</div>
						<div class="col s12 input-field">
							<input type="text" id="login" name="login" value="<?= $res->login_usuario; ?>">
							<label for="login">Nome do botão</label>
						</div>
						<div class="col s12 input-field">
							<a href="#modal2" class="modal-trigger">Alterar Senha</a>
							<div id="modal2" class="modal">
								<style type="text/css">
									#password_again-error{
										top: 30px !important;
									}
								</style>
								<div class="modal-content">
	
									<h5>Alterar senha</h5>
									<div class="col s12 input-field">
										<input type="password" id="password" name="password">
										<label for="password">Senha</label>
									</div>
									<div class="col s12 input-field">
										<input type="password" id="password_again" name="password_again">
										<label for="password_again">Confirmar Senha</label>
									</div>
									<div class="col s6">
										<input type="submit" name="alterarSenha" value="Alterar" class="btn bg_color">

										<?php 
										if (isset($_POST['alterarSenha'])) {
										    
										    ?>
										    							    <script>
        function validarFormulario() {
            var senha = document.getElementById('password').value;
            var confirmacaoSenha = document.getElementById('password_again').value;

            if (senha !== confirmacaoSenha) {
                alert('As senhas não coincidem. Por favor, tente novamente.');
                return false; // Impede o envio do formulário
            }

            // Outras validações de segurança podem ser adicionadas aqui

            return true; // Permite o envio do formulário
        }
    </script>
										    <?php
											$usuarios = new usuarios(array(
												"senha_usuario" => hash_password($_POST['password']),
											));
											$usuarios->valorpk=$id;
											$usuarios->atualizar($usuarios);
											if ($usuarios->linhasafetadas > 0) {
												header("Location: ?m=usuarios&t=editar&id=".$id."&status=updatesenha");

											}else{
												header("Location: ?m=usuarios&t=editar&id=".$id."&status=erro");
											}
										}
										?>
									</div>
									<div class="col s6">
										<a href="#!" class="modal-close waves-effect waves-green btn-flat bg_escuro right-align right-float">Cancelar</a>
									</div>
									<div class="modal-footer"></div>
								</div>
							</div>
						</div>
						
					
					</div>
					<div class="col s12 m12 l3">
						<div class="col s12  input-field">
							<input type="submit" name="atualizar" value="Atualizar" class="btn bg_color" >
						</div>
						<div class="col s12">
							<a  href="#modal1" class="red-text  modal-trigger">Excluir esse usuário.</a>
						</div>
						<div id="modal1" class="modal">
							<div class="modal-content">
								<h5>Deseja excluir esse Usuário?</h5>

								<div class="modal-footer">
									<a href="<?= BASEURL ?>painel.php?m=usuarios&t=trash&id=<?= $res->id ?>" class="waves-effect waves-green btn-flat bg_escuro right-float">Sim</a>
									<a href="#!" class="modal-close waves-effect waves-green btn-flat bg_escuro right-float">Não</a>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>


			<?php
		
	}else{
		echo "post não existe";
	}
	break;

	case 'trash':

	if (isset($_GET['id'])) {
		$id = $_GET['id'];

		$usuarios = new usuarios(array(
			"status" => '0',
		));
		$usuarios->valorpk=$id;
		$usuarios->atualizar($usuarios);
		if ($usuarios->linhasafetadas > 0) {
			?>
			<div class="row">
				<div class="container">
					<div id="modal2" class="modal" style="display: block;">
						<div class="modal-content">
							<h5>Usuário excluido com sucesso!</h5>

							<div class="modal-footer">
								<a href="<?= BASEURL ?>painel.php?m=usuarios&t=listar" class="modal-close waves-effect waves-green btn-flat bg_escuro right-float">Sair</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
		}
		?>
		<?php 
	}
	break;

	case 'token':
		
	break;
	// TELA NÃO EXISTE
	default:
	echo '<p>A tela solicitada não existe</p>';
	break;
}
?>


