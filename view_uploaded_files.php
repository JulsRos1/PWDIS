<?php
session_start();
include('includes/config.php');
if (!isset($_SESSION['user_login'])) {
    header("Location: user_login.php");
    exit;
} else {
    $query = mysqli_query($con, "SELECT * FROM uploaded_files ORDER BY date_created DESC");
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Uploaded Files</title>
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="css/sidebar.css" rel="stylesheet">
        <style>
            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
            }

            .content-page {
                flex: 1;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            .file-card {
                margin-bottom: 30px;
                box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
                border-radius: 12px;
                transition: 0.3s;
                padding: 20px;
                margin-top: 30px;
            }

            .file-card:hover {
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            }

            .page-title {
                margin-top: 20px;
            }

            .file-icon {
                font-size: 30px;
                color: #007bff;
            }

            .file-details {
                padding: 10px;
            }

            .file-buttons a {
                margin-right: 5px;
                margin-bottom: 5px;
            }

            .card-title {
                margin-bottom: 5px;
                font-size: 0.9rem;
                text-overflow: ellipsis;
                overflow: hidden;
            }

            .card-text {
                font-size: 0.8rem;
            }

            @media (max-width: 767px) {
                .file-card {
                    max-width: 100%;
                }

                .container {
                    margin-top: 10px;
                    margin-left: 0;
                }

                .file-icon {
                    font-size: 24px;
                }

                .card-title {
                    font-size: 0.85rem;
                }

                .file-name,
                .card-text {
                    font-size: 0.75rem;
                }

                .file-buttons a {
                    font-size: 0.75rem;
                    padding: 0.2rem 0.4rem;
                }
            }
        </style>
    </head>

    <body class="fixed-left">
        <div class="top-header">
            <div class="logo-header">
                <a href="index.php"><i class='fa fa-wheelchair'></i><span>PWD<span>IS</span></span></a>
            </div>
            <button class="openbtn" onclick="toggleNav()">&#9776;</button>
        </div>
        <?php include("includes/sidebar.php"); ?>
        <div id="main">
            <div id="wrapper1">
                <div class="content-page">
                    <div class="content">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box">
                                        <h4 class="page-title">Disability Support Materials</h4>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                while ($row = mysqli_fetch_array($query)) {
                                    $fileExtension = pathinfo($row['file_name'], PATHINFO_EXTENSION);
                                    $iconClass = '';
                                    if ($fileExtension == 'pdf') {
                                        $iconClass = 'fas fa-file-pdf';
                                    } elseif ($fileExtension == 'doc' || $fileExtension == 'docx') {
                                        $iconClass = 'fas fa-file-word';
                                    } else {
                                        $iconClass = 'fas fa-file-alt';
                                    }
                                ?>

                                    <div class="col-md-6 col-lg-4 col-12">
                                        <div class="card file-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="file-icon mr-3">
                                                        <i class="<?php echo $iconClass; ?>"></i>
                                                    </div>
                                                    <div class="file-details">
                                                        <h5 class="card-title"><?php echo htmlentities($row['title']); ?></h5>
                                                    </div>
                                                </div>
                                                <div class="file-buttons mt-2">
                                                    <a href="admin/uploaded_files/<?php echo htmlentities($row['file_name']); ?>" target="_blank" class="btn btn-info btn-sm">View</a>
                                                    <a href="admin/uploaded_files/<?php echo htmlentities($row['file_name']); ?>" class="btn btn-success btn-sm" download>Download</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                    <?php include('includes/footer.php'); ?>
                </div>
            </div>

            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script>
                function toggleNav() {
                    const sidebar = document.getElementById("mySidebar");
                    if (sidebar.style.width === "250px") {
                        closeNav();
                    } else {
                        openNav();
                    }
                }
                // Function to open sidebar
                function openNav() {
                    document.getElementById("mySidebar").style.width = "250px";
                    document.getElementById("main").style.marginLeft = "250px";
                }

                // Function to close sidebar
                function closeNav() {
                    document.getElementById("mySidebar").style.width = "0";
                    document.getElementById("main").style.marginLeft = "0";
                }
            </script>
    </body>

    </html>
<?php } ?>