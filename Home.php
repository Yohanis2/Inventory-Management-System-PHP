<?php include 'header.php'; ?>
<style>.carousel-caption {
    background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent black background */
    color: #ffffff; /* White text */
    padding: 20px;
    border-radius: 10px;
}

.carousel-caption h1,
.carousel-caption p {
    color: #ffffff; /* Ensures both heading and paragraph have white color */
}

.carousel-caption .btn-primary {
    background-color: #007bff; /* Bootstrap primary blue */
    border-color: #007bff;
    color: #fff;
}

.carousel-caption .btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}
</style>


<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2 class="icon icon-home"><i class="fas fa-home"></i> Home</h2>
            <nav>
                <img src="img/logo1.jpg" alt="BU Service Logo" class="img-fluid">
            </nav>
        </aside>
        <!-- Main Content -->
        <section class="col-md-9">
            <div class="main">
                <div id="homepage-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="img/Home.jpg" class="d-block w-100" alt="Bonga University Campus">
                        </div>
                        <div class="carousel-item">
                            <img src="img/piter.jpg" class="d-block w-100" alt="Inventory Management System">
                        </div>
                        
                        <div class="carousel-item">
                            <img src="img/invntory2.jpg" class="d-block w-100" alt="Bonga University Logo">
                        </div>
                        <div class="carousel-item">
                            <img src="img/about.jpg" class="d-block w-100" alt="Bonga University Logo">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#homepage-carousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#homepage-carousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    
                    <!-- Carousel Text Overlay -->
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="animated ScrollRight">Bonga University Inventory Management System Platform</h1>
                        <p class="animated ScrollRight">TOGETHER WE CAN</p>
                        <a href="https://bongau.edu.et/" target="_blank" class="btn btn-primary animated ScrollRight">Visit Website</a>
                    </div>
                </div>
                
                <p>
                    Bonga University store office is the backbone of the University by managing any activities 
                    related to materials available on the University.<br>
                    Nowadays, Bonga University store office provides many services; among these services,
                    the following are the main services:
                </p>
                
                <ul>
                    <li>Registering new materials</li>
                    <li>Generating reports periodically</li>
                    <li>Offering materials to the users</li>
                    <li>Receiving materials from the users</li>
                </ul>
            </div>
        </section>
    </div>
</main>

<?php include 'footer.php'; ?>

