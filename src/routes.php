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

    //---------------------------------------------------PoliKlinik--------------------------------------------------

    $app->get("/poliklinik/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM poliklinik";
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

    $app->get("/poliklinik/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM petugas WHERE id_poli=:id";
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

    $app->get("/poliklinik/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM poliklinik WHERE nama_poli LIKE '%$keyword%' OR gedung LIKE '%$keyword%'";
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

    $app->post("/poliklinik/", function (Request $request, Response $response) {

        $new_book = $request->getParsedBody();

        $sql = "INSERT INTO poliklinik (nama_poli, gedung) VALUE (:nama_poli, :gedung)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":nama_poli" => $new_book["nama_poli"],
            ":gedung" => $new_book["gedung"],
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->put("/poliklinik/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE poliklinik SET nama_poli=:nama_poli, gedung=:gedung WHERE id_poli=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":nama_poli" => $new_book["nama_poli"],
            ":gedung" => $new_book["gedung"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->delete("/poliklinik/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM poliklinik WHERE id_poli=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    //---------------------------------------------------Diagnosa--------------------------------------------------

    $app->get("/diagnosa/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM diagnosa";
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

    $app->get("/diagnosa/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM rawat_inap WHERE id_diagnosa=:id";
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

    $app->get("/diagnosa/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM diagnosa WHERE keluhan LIKE '%$keyword%' OR penyakit LIKE '%$keyword%'";
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

    $app->post("/diagnosa/", function (Request $request, Response $response) {

        $new_book = $request->getParsedBody();

        $sql = "INSERT INTO diagnosa (keluhan, penyakit) VALUE (:keluhan, :penyakit)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":keluhan" => $new_book["keluhan"],
            ":penyakit" => $new_book["penyakit"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->put("/diagnosa/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE diagnosa SET keluhan=:keluhan, penyakit=:penyakit WHERE id_diagnosa=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":keluhan" => $new_book["keluhan"],
            ":penyakit" => $new_book["penyakit"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->delete("/diagnosa/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM diagnosa WHERE id_diagnosa=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
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
    
//====================================================Obat===============================================================
    $app->get("/obat/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM obat";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
         $result = $stmt->fetchAll();
        return $response->withJson(["success" => "true", "code_resons" => "200", "data" => $result], 200);
    });
    
    $app->get("/obat/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM obat WHERE id_obat=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["success" => "true", "code_resons" => "200", "data" => $result], 200);
    });
    
    $app->get("/obat/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM obat WHERE nama_obat LIKE '%$keyword%' OR ket_obat LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["success" => "true", "code_resons" => "200", "data" => $result], 200);
    });
    
    $app->post("/obat/", function (Request $request, Response $response) {
    
    $new_book = $request->getParsedBody();
    
    $sql = "INSERT INTO obat (nama_obat, ket_obat) VALUE (:nama_obat, :ket_obat)";
    $stmt = $this->db->prepare($sql);
    
        $data = [
        ":nama_obat" => $new_book["nama_obat"],
        ":ket_obat" => $new_book["ket_obat"]
    ];
    
    if ($stmt->execute($data))
        return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);
    
        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });
    
    $app->put("/obat/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE obat SET nama_obat=:nama_obat, ket_obat=:ket_obat WHERE id_obat=:id";
        $stmt = $this->db->prepare($sql);
    
            $data = [
                ":id" => $id,
                ":nama_obat" => $new_book["nama_obat"],
                ":ket_obat" => $new_book["ket_obat"]
            ];
    
            if ($stmt->execute($data))
                return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);
    
            return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
        });
    
    $app->delete("/obat/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM obat WHERE id_obat=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
         ];

        if ($stmt->execute($data))
             return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);
    
        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

//===================-==============================rm_obat==============================================================
    $app->get("/rm_obat/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM rm_obat";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["success" => "true", "code_resons" => "200", "data" => $result], 200);
    });
    
    $app->get("/rm_obat/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM rm_obat WHERE id_rm_obat=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["success" => "true", "code_resons" => "200", "data" => $result], 200);
    });
    
    $app->get("/rm_obat/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM rm_obat WHERE id_rekamedis LIKE '%$keyword%' OR id_obat LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["success" => "true", "code_resons" => "200", "data" => $result], 200);
    });
    
    $app->post("/rm_obat/", function (Request $request, Response $response) {
    
        $new_book = $request->getParsedBody();
    
        $sql = "INSERT INTO rm_obat (id_rekamedis, id_obat) VALUE (:id_rekamedis, :id_obat)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":id_rekamedis" => $new_book["id_rekamedis"],
            ":id_obat" => $new_book["id_obat"]
        ];
    
        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);
    
        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });
    
    $app->put("/rm_obat/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE rm_obat SET id_rekamedis=:id_rekamedis, id_obat=:id_obat WHERE id_rm_obat=:id";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":id" => $id,
            ":id_rekamedis" => $new_book["id_rekamedis"],
            ":id_obat" => $new_book["id_obat"]
        ];
    
        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);
    
        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });
    
    $app->delete("/rm_obat/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM rm_obat WHERE id_rm_obat=:id";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":id" => $id
        ];
    
        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);
    
        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });
// };
    //============================================ Pasien ===========================================================

    $app->get("/pasien/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM pasien";
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

    $app->get("/pasien/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM pasien WHERE id_pasien=:id";
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

    $app->get("/pasien/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM pasien WHERE nama_pasien LIKE '%$keyword%' OR jenis_kelamin LIKE '%$keyword%' OR alamat LIKE '%$keyword%' OR no_telp LIKE '%$keyword%$'";
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

    $app->post("/pasien/", function (Request $request, Response $response) {

        $new_book = $request->getParsedBody();

        $sql = "INSERT INTO pasien (nama_pasien, jenis_kelamin, alamat, no_telp) VALUE (:nama_petugas, :jenis_kelamin, :alamat, :no_telp)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":nama_pasien" => $new_book["nama_pasien"],
            ":jenis_kelamin" => $new_book["jenis_kelamin"],
            ":alamat" => $new_book["alamat"],
            ":no_telp" => $new_book["no_telp"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->put("/pasien/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE pasien SET nama_pasien=:nama_pasien, jenis_kelamin=:jenis_kelamin, alamat=:alamat, no_telp=:no_telp WHERE id_pasien=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":nama_pasien" => $new_book["nama_pasien"],
            ":jenis_kelamin" => $new_book["jenis_kelamin"],
            ":alamat" => $new_book["alamat"],
            ":no_telp" => $new_book["no_telp"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->delete("/pasien/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM pasien WHERE id_pasien=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    //=================================================== Pembayaran ======================================================

    $app->get("/pembayaran/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM pembayaran";
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

    $app->get("/pembayaran/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "SELECT * FROM pembayaran WHERE id_bayar=:id";
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

    $app->get("/pembayaran/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM pembayaran WHERE id_pasien LIKE '%$keyword%' OR jumlah_bayar LIKE '%$keyword%'";
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

    $app->post("/pembayaran/", function (Request $request, Response $response) {

        $new_book = $request->getParsedBody();

        $sql = "INSERT INTO pembayaran (id_pasien, jumlah_bayar) VALUE (:id_pasien, :jumlah_bayar)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id_pasien" => $new_book["id_pasien"],
            ":jumlah_bayar" => $new_book["jumlah_bayar"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->put("/pembayaran/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE pembayaran SET id_pasien=:id_pasien, jumlah_bayar=:jumlah_bayar WHERE id_bayar=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":id_pasien" => $new_book["id_pasien"],
            ":jumlah_bayar" => $new_book["jumlah_bayar"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });

    $app->delete("/pembayaran/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $sql = "DELETE FROM pembayaran WHERE id_bayar=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id
        ];

        if ($stmt->execute($data))
            return $response->withJson(["success" => "true", "code_resons" => "200", "data" => "1"], 200);

        return $response->withJson(["success" => "false", "code_resons" => "400", "message" => "sorry,that page does not exist"], 200);
    });
};




