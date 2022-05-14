<?php 
include ("ClassConexao.php");

class ClassVisitas extends ClassConexao {

	private $id, $Ip, $Data, $Hora, $Limit;


	public function __construct() 
	{
		$this->Id = 0;
		$this->Ip = $_SERVER['REMOTE_ADDR'];
		$this->Data = date("Y/m/d");
		$this->Hora = date("H:i");
		$this->Limite = 50;
	}

	public function VerificaUsuario()
	{
		$Select = $this->Conectar() ->prepare("SELECT * FROM visitas WHERE Ip=:ip AND Data=:datas ORDER BY id DESC");
		$Select->bindParam(":ip",$this->Ip, PDO::PARAM_STR);
		$Select->bindParam(":datas",$this->Data, PDO::PARAM_STR);
		$Select->execute();
		if($Select->rowCount() == 0){
			$this->InserirVisitas();
		}else{
			$Fselect = $Select->fetch(PDO::FETCH_ASSOC);
			$HoraDB= strtotime($Fselect['Hora']);
			$HoraActual = strtotime($this->Hora);
			$HoraSubtracao = $HoraActual - $HoraDB;
			echo $HoraSubtracao;

			if($HoraSubtracao > $this->Limite) {
				$this->InserirVisitas();
			}else{
				return "Usuario recente - Visita nao Incluida";
			}
		}
		echo "<h2>Visitantes no Site: ".$Select->rowCount()."</h2";
	}

	private function InserirVisitas()
	{
		$Insert = $this->Conectar() ->prepare("INSERT INTO visitas  VALUES (:id, :ip, :datas, :hora)");
		$Insert->bindParam(":id", $this->id, PDO::PARAM_STR);
		$Insert->bindParam(":ip", $this->Ip, PDO::PARAM_STR);
		$Insert->bindParam(":datas", $this->Data, PDO::PARAM_STR);
		$Insert->bindParam(":hora", $this->Hora, PDO::PARAM_STR);
		$Insert->execute();
	}

}