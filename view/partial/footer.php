    </div> <!-- Close content-wrapper -->

    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold">
                        <i class="fas fa-graduation-cap me-2"></i>CodeMastery
                    </h5>
                    <p class="text-white-50">Master coding skills that get you hired. Join thousands of students transforming their careers.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6 class="fw-bold">Platform</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_PATH ?>/courses" class="text-white-50 text-decoration-none">Courses</a></li>
                        <li><a href="<?= BASE_PATH ?>/pricing" class="text-white-50 text-decoration-none">Pricing</a></li>
                        <li><a href="<?= BASE_PATH ?>/blog" class="text-white-50 text-decoration-none">Blog</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h6 class="fw-bold">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_PATH ?>/about" class="text-white-50 text-decoration-none">About</a></li>
                        <li><a href="<?= BASE_PATH ?>/contact" class="text-white-50 text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Help Center</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold">Newsletter</h6>
                    <p class="text-white-50 small mb-3">Get the latest course updates and programming tips.</p>
                    <form method="POST" action="" class="d-flex gap-2">
                        <input type="email" name="newsletter_email" class="form-control" placeholder="Enter your email" required>
                        <button type="submit" class="btn btn-light">
                            <i class="fas fa-paper-plane text-dark"></i>
                        </button>
                    </form>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-white-50 mb-0">&copy; 2024 CodeMastery. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex gap-3 justify-content-md-end justify-content-start">
                        <a href="#" class="text-white-50 text-decoration-none small">Privacy Policy</a>
                        <a href="#" class="text-white-50 text-decoration-none small">Terms of Service</a>
                        <a href="#" class="text-white-50 text-decoration-none small">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_PATH ?>/assets/js/main.js"></script>
</body>
</html>