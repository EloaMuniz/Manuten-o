<?php

header("Content-Type: application/json");

require "conexao.php";

$metodo = $_SERVER["REQUEST_METHOD"];


if($metodo == "POST"){

    $json = file_get_contents("php://input");

    $dados = json_decode($json, true);

    if(
        !isset($dados["equipamento"]) ||
        !isset($dados["setor"]) ||
        !isset($dados["descricao"]) ||
        !isset($dados["prioridade"]) ||
        !isset($dados["status"])
    ){

        echo json_encode(["Mensagem" => "Preencha todos os campos obrigatórios!"]);
        exit;
    }

    $prioridades = ["baixa", "media", "alta"];

    $status = ["aberto", "em andamento", "concluido"];

    if(!in_array($dados["prioridade"], $prioridades)){

        echo json_encode(["Mensagem" => "Prioridade inválida!"]);
        exit;
    }

    if(!in_array($dados["status"], $status)){

        echo json_encode(["Mensagem" => "Status inválido!"]);
        exit;
    }

    $sql = "INSERT INTO chamados (equipamento,setor,descricao,prioridade,status) VALUES (?,?,?,?,?)";

    $comando = $pdo->prepare($sql);

    $comando->execute([
        $dados["equipamento"],
        $dados["setor"],
        $dados["descricao"],
        $dados["prioridade"],
        $dados["status"]
    ]);

    echo json_encode(["Mensagem" => "Chamado cadastrado com sucesso!"]);
}


if($metodo == "GET"){

    $sql = "SELECT * FROM chamados ORDER BY id";

    $comando = $pdo->query($sql);

    $chamados = $comando->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($chamados);
}


if($metodo == "PUT"){

    $json = file_get_contents("php://input");

    $dados = json_decode($json, true);

    if(
        !isset($dados["id"]) ||
        !isset($dados["equipamento"]) ||
        !isset($dados["setor"]) ||
        !isset($dados["descricao"]) ||
        !isset($dados["prioridade"]) ||
        !isset($dados["status"])
    ){

        echo json_encode(["Mensagem" => "Preencha todos os campos obrigatórios!"]);
        exit;
    }

    $prioridades = ["baixa", "media", "alta"];

    $status = ["aberto", "em andamento", "concluido"];

    if(!in_array($dados["prioridade"], $prioridades)){

        echo json_encode(["Mensagem" => "Prioridade inválida!"]);
        exit;
    }

    if(!in_array($dados["status"], $status)){

        echo json_encode(["Mensagem" => "Status inválido!"]);
        exit;
    }

    $sql = "UPDATE chamados SET equipamento=?,setor=?,descricao=?,prioridade=?,status=? WHERE id=?";

    $comando = $pdo->prepare($sql);

    $comando->execute([
        $dados["equipamento"],
        $dados["setor"],
        $dados["descricao"],
        $dados["prioridade"],
        $dados["status"],
        $dados["id"]
    ]);

    echo json_encode(["Mensagem" => "Chamado atualizado com sucesso!"]);
}


if($metodo == "DELETE"){

    $json = file_get_contents("php://input");

    $dados = json_decode($json, true);

    if(!isset($dados["id"])){

        echo json_encode(["Mensagem" => "Informe o id do chamado!"]);
        exit;
    }

    $sql = "DELETE FROM chamados WHERE id=?";

    $comando = $pdo->prepare($sql);

    $comando->execute([
        $dados["id"]
    ]);

    echo json_encode(["Mensagem" => "Chamado excluído com sucesso!"]);
}