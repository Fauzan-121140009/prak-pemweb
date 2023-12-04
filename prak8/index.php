<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "praktikum";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

$nama = "";
$nim = "";
$prodi = "";
$email = "";
$gambar = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM mahasiswa WHERE id = $id";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Data gagal dihapus";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM mahasiswa WHERE id = $id";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nama = $r1['nama'];
    $nim = $r1['nim'];
    $prodi = $r1['prodi'];
    $email = $r1['email'];
    $gambar = $r1['gambar'];
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];
    $email = $_POST['email'];
    $gambar = upload();

    if ($nama && $nim && $prodi && $email && $gambar) {
        if ($op == 'edit') {
            $sql1 = "UPDATE mahasiswa SET nama = '$nama', nim = '$nim', prodi = '$prodi', email = '$email', gambar = '$gambar' WHERE id = $id";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data Berhasil diupdate";
            } else {
                $error = "Data Gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO mahasiswa (nama, nim, prodi, email, gambar) VALUES ('$nama', '$nim', '$prodi', '$email', '$gambar')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil Memasukkan Data Baru";
            } else {
                $error = "Gagal Memasukkan Data";
            }
        }
    } else {
        $error = "Silahkan Masukkan Semua Data";
    }
}

function upload()
{
    $namaGambar = $_FILES['gambar']['name'];
    $ukuranGambar = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if ($error === 4) {
        echo "<script>alert('Pilih gambar terlebih dahulu');</script>";
        return false;
    }

    $exstensigambarvalid = ['jpg', 'jpeg', 'png'];
    $exstensigambar = pathinfo($namaGambar, PATHINFO_EXTENSION); // Use pathinfo to get file extension

    if (!in_array($exstensigambar, $exstensigambarvalid)) {
        echo "<script>alert('Yang anda upload bukan gambar');</script>";
        return false;
    }

    if ($ukuranGambar > 1000000) {
        echo "<script>alert('ukuran gambar terlalu besar');</script>";
        return false;
    }

    $namafilebaru = uniqid();
    $namafilebaru .= '.';
    $namafilebaru .= $exstensigambar;

    move_uploaded_file($tmpName, 'image/' . $namafilebaru);

    return $namafilebaru;
}
?>

<!-- Your HTML code remains the same -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px
        }
    </style>

</head>

<body>
    <div class="mx-auto">
        <div class="card ">
            <!--memasukkan data -->
            <div class="card-header bg-primary text-dark">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success " role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST" enctype="multipart/form-data">


                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="prodi" class="col-sm-2 col-form-label">Program Studi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="prodi" name="prodi" value="<?php echo $prodi ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="gambar" name="gambar" value="<?php echo $gambar ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="simpan" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <!--mengeluarkan data-->
        <div class="card ">
            <div class="card-header bg-primary text-dark">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Program Studi</th>
                            <th scope="col">Email</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    <tbody>
                        <?php
                        $sql2 = "select * from  mahasiswa order by id desc";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id'];
                            $nama = $r2['nama'];
                            $nim = $r2['nim'];
                            $prodi = $r2['prodi'];
                            $email = $r2['email'];
                            $gambar = $r2['gambar'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td><?php echo  $nama ?></td>
                                <td><?php echo $nim ?></td>
                                <td><?php echo $prodi ?></td>
                                <td><?php echo $email ?></td>
                                <td><img src="image/<?php echo $gambar; ?>" width="50"></td>
                                <td>
                                    <a class="m" href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-danger">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-warning">Delete</button></a>

                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>