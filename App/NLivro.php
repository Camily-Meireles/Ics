<?php

	namespace App;

	class NLivro{
		public function read(){
			require_once 'Conexao.php';
			$sql = "SELECT * FROM Livros_leitura";
    			$resultado = mysqli_query($connect, $sql);
			return $resultado;
		}
		public function gerar($type){
			$min = 0;
			$max = 0;
			if($type == "sm"){
				$min = 1;
				$max = 250;
			}
			elseif($type == "md") {
				$min = 251;
				$max = 400;
			}
			else{
				$min = 401;
				$max = 100000000;
			}

			$sql = "SELECT * FROM Livros_leitura WHERE id = ?";
			$stmt = Conexao::getConn()->prepare($sql);
			$total = count($this->read());
			do{
				$rand = random_int(1, $total);
				$sql = "SELECT * FROM livros_leitura where id = ?";
				$stmt = Conexao::getConn()->prepare($sql);
				$stmt->bindValue(1, $rand);
				$stmt->execute();
				$res = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];
			} while($res['num_pags'] < $min or $res['num_pags'] > $max);
			$sql = "UPDATE Livros_leitura set situacao = ? where id = ?";
			$stmt = Conexao::getConn()->prepare($sql);
			$stmt->bindValue(1, "S");
			$stmt->bindValue(2, $rand);
			$stmt->execute();
			return $res;
		}

		public function sorteados(){
			$sql = "SELECT * FROM Livros_leitura where situacao = 'S'";
			$resultado = mysqli_query($connect, $sql);
			return mysqli_fetch_array($resultado);
		}

		public function retirar($id){
			$sql = "UPDATE Livros_leitura set situacao = ? where id = ?";
			$stmt = Conexao::getConn()->prepare($sql);
			$stmt->bindValue(1, "N");
			$stmt->bindValue(2, $id);
			if($stmt->execute())return "yes";
			else return "no";
		}

		public function getLivro($id){
			require_once 'Conexao.php';
			$sql = "SELECT * FROM Livros_leitura WHERE id = '".$id."'";
			$resultado = mysqli_query($connect, $sql);
			return mysqli_fetch_array($resultado);
		}

		public function add(){
			if(isset($_POST['enviar'])){
				$servername = "192.168.100.20";
				$username = "ifrn";
				$password = "ifrn";
				$db_name = "sorteio";

				$connect = mysqli_connect($servername, $username, $password, $db_name);

				if(mysqli_connect_error()){
					echo "Falha na conexão: ".mysqli_connect_error();
				}

				$sql = "INSERT INTO Livros_leitura (nome, num_pags, status) values ('".$_POST['nome']."', ".$_POST['num_pags']." , 'N')";
				try{
					if(mysqli_query($connect, $sql)) header("Location: index.php");
					else header("Location: AddLivro.php?error=1");
				}
				catch (Exception $e){
					header("Location: AddLivro.php?error=2");
				}
			}
		}

		public function edit($id){
			$servername = "192.168.100.20";
			$username = "ifrn";
			$password = "ifrn";
			$db_name = "sorteio";

			$connect = mysqli_connect($servername, $username, $password, $db_name);

			if(mysqli_connect_error()){
				echo "Falha na conexão: ".mysqli_connect_error();
			}
			if(isset($_POST['enviar'])){
				if($connect != null){
					$sql = "UPDATE Livros_leitura set nome = '".$_POST['nome']."', num_pags = '".$_POST['num_pags']."', status = 'N' where id = '".$id."'";
					try{
						$_SESSION['error'] = $connect;
						if(mysqli_query($connect, $sql)) header("Location: index.php");
						else header("Location: EditarLivro.php?error=1");
					}
					catch (Exception $e){
						header("Location: EditarLivro.php?error=2");
					}
				}
				else header("Location: EditarLivro.php?error=3");

			}
		}

		public function remove($id){
			require_once 'Conexao.php';
			$sql = "DELETE FROM Livros_leitura where id = '".$id."'";
			try{
				if(mysqli_query($connect, $sql))return "yes";
				else return "no";
			}
			catch (Exception $e){
				return "no1";
			}
		}
	}