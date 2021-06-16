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

    //---------------------------------------------------Dokter --------------------------------------------------

    $app->get("/dokter/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM dokter";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/dokter/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM dokter WHERE id_dokter=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/dokter/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM dokter WHERE nama_dokter LIKE '%$keyword%' OR spesialis LIKE '%$keyword%' OR no_telp LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
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
        $sql = "UPDATE dokter SET nama_dokter=:nama_dokter, no_telp=:no_telp, spesialis=:spesialis, alamat=:alamat WHERE spesialis=:id";
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

    //---------------------------------------------------Rekamedis --------------------------------------------------

    $app->get("/rekamedis/", function (Request $request, Response $response) {
        $sql = "SELECT
                id_rekamedis, nama_pasien, nama_dokter, penyakit, nama_poli
            FROM rekamedis
                JOIN pasien USING(id_pasien)
                JOIN dokter USING(id_dokter)
                JOIN diagnosa USING(id_diagnosa)
                JOIN poliklinik USING(id_poli)
                ORDER BY 
                id_rekamedis ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/rekamedis/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT
            id_rekamedis, nama_pasien, nama_dokter, penyakit, nama_poli
        FROM rekamedis
            JOIN pasien USING(id_pasien)
            JOIN dokter USING(id_dokter)
            JOIN diagnosa USING(id_diagnosa)
            JOIN poliklinik USING(id_poli)
            WHERE 
            id_rekamedis=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/rekamedis/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT
        id_rekamedis, nama_pasien, nama_dokter, penyakit, nama_poli, tgl_periksa
    FROM rekamedis
        JOIN pasien USING(id_pasien)
        JOIN dokter USING(id_dokter)
        JOIN diagnosa USING(id_diagnosa)
        JOIN poliklinik USING(id_poli) WHERE id_pasien LIKE '%$keyword%' OR id_dokter LIKE '%$keyword%' OR tgl_periksa LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->post("/rekamedis/", function (Request $request, Response $response) {

        $new_book = $request->getParsedBody();

        $sql = "INSERT INTO rekamedis (id_pasien, id_diagnosa, id_dokter, id_poli, tgl_periksa) VALUE (:id_pasien, :id_diagnosa, :id_dokter, :id_poli, :tgl_periksa)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id_pasien" => $new_book["id_pasien"],
            ":id_dokter" => $new_book["id_dokter"],
            ":id_poli" => $new_book["id_poli"],
            ":id_diagnosa" => $new_book["id_diagnosa"],
            ":tgl_periksa" => $new_book["tgl_periksa"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->put("/rekamedis/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE rekamedis SET id_pasien=:id_pasien, id_diagnosa=:id_diagnosa, id_dokter=:id_dokter, id_poli=:id_poli, tgl_periksa=:tgl_periksa WHERE id_rekamedis=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":id_pasien" => $new_book["id_pasien"],
            ":id_diagnosa" => $new_book["id_diagnosa"],
            ":id_poli" => $new_book["id_poli"],
            ":id_dokter" => $new_book["id_dokter"],
            ":tgl_periksa" => $new_book["tgl_periksa"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->delete("/rekamedis/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM rekamedis WHERE id_rekamedis=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });


    // petugas and rawat_inap
    $app->get("/petugas/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM petugas";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/rawat_inap/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM rawat_inap";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/petugas/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM petugas WHERE id_petugas=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/rawat_inap/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM rawat_inap WHERE id_ruang=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/petugas/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM petugas WHERE nama_petugas LIKE '%$keyword%' OR jenis_kelamin LIKE '%$keyword%' OR no_telp LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->get("/rawat_inap/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM rawat_inap WHERE id_rekamedis LIKE '%$keyword%' OR nama_ruangan LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($result != null) {
            return $response->withJson(
                [
                    "success" => "true",
                    "code_resons" => "200",
                    "data" => $result
                ],
                200
            );
        } else {
            return $response->withJson(
                [
                    "success" => "false",
                    "code_resons" => "400",
                    "message" => "sorry,that page does not exist"
                ],
                400
            );
        }
    });

    $app->post("/petugas/", function (Request $request, Response $response) {

        $new_book = $request->getParsedBody();

        $sql = "INSERT INTO petugas (nama_petugas, jenis_kelamin, no_telp) VALUE (:nama_petugas, :jenis_kelamin, :no_telp)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":nama_petugas" => $new_book["nama_petugas"],
            ":jenis_kelamin" => $new_book["jenis_kelamin"],
            ":no_telp" => $new_book["no_telp"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->post("/rawat_inap/", function (Request $request, Response $response) {

        $new_book = $request->getParsedBody();

        $sql = "INSERT INTO rawat_inap (id_rekamedis, nama_ruangan) VALUE (:id_rekamedis, :nama_ruangan)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id_rekamedis" => $new_book["id_rekamedis"],
            ":nama_ruangan" => $new_book["nama_ruangan"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->put("/petugas/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE petugas SET nama_petugas=:nama_petugas, jenis_kelamin=:jenis_kelamin, no_telp=:no_telp WHERE id_petugas=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":nama_petugas" => $new_book["nama_petugas"],
            ":jenis_kelamin" => $new_book["jenis_kelamin"],
            ":no_telp" => $new_book["no_telp"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->put("/rawat_inap/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE rawat_inap SET id_rekamedis=:id_rekamedis, nama_ruangan=:nama_ruangan WHERE id_ruang=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":id_rekamedis" => $new_book["id_rekamedis"],
            ":nama_ruangan" => $new_book["nama_ruangan"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->delete("/petugas/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM petugas WHERE id_petugas=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->delete("/rawat_inap/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM rawat_inap WHERE id_ruang=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });
};
