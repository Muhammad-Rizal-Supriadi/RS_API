<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get("/dokter/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM dokter";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null){
            return $response->withJson([
                "success" => "true",
                "code_resons" => "200",
                "data" => $result],
            200);
        }else{
            return $response->withJson([
                "success" => "false", 
                "code_resons" => "400", 
                "message" => "sorry,that page does not exist"],
            400);
        }
    });

    $app->get("/dokter/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM dokter WHERE id_dokter=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if ($result != null){
            return $response->withJson([
                "success" => "true",
                "code_resons" => "200",
                "data" => $result],
            200);
        }else{
            return $response->withJson([
                "success" => "false", 
                "code_resons" => "400", 
                "message" => "sorry,that page does not exist"],
            400);
        }   
    });

    $app->get("/dokter/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM dokter WHERE nama_dokter LIKE '%$keyword%' OR spesialis LIKE '%$keyword%' OR no_telp LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null){
            return $response->withJson([
                "success" => "true",
                "code_resons" => "200",
                "data" => $result],
            200);
        }else{
            return $response->withJson([
                "success" => "false", 
                "code_resons" => "400", 
                "message" => "sorry,that page does not exist"],
            400);
        }
    });

    $app->post("/dokter/", function (Request $request, Response $response) {

        $new_book = $request->getParsedBody();

        $sql = "INSERT INTO dokter (nama_dokter, no_telp, spesialis, alamat) VALUE (:nama_dokter, :no_telp, :spesialis, :alamat)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":nama_dokter" => $new_book["nama_dokter"],
            ":spesialis" => $new_book["spesialis"],
            ":alamat" => $new_book["alamat"],
            ":no_telp" => $new_book["no_telp"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->put("/dokter/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE dokter SET nama_dokter=:nama_dokter, no_telp=:no_telp, spesialis=:spesialis, alamat=:alamat WHERE id_dokter=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":nama_dokter" => $new_book["nama_dokter"],
            ":no_telp" => $new_book["no_telp"],
            ":alamat" => $new_book["alamat"],
            ":spesialis" => $new_book["spesialis"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->delete("/dokter/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM dokter WHERE id_dokter=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });
};
