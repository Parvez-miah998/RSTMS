<footer id="footer" style="margin: 0px;padding: 1px;">
        <h5 style="text-align: center;">Copyright &copy; 2024 || Powered by Kryzotech</h5>
    </footer>
</body>
<!-- 
.................../ JS Code for smooth scrolling /...................... -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Add smooth scrolling to all links
        $("a").on("click", function (event) {
            // Make sure this.hash has a value before overriding default behavior
            if (this.hash !== "") {
                // Prevent default anchor click behavior
                event.preventDefault();

                // Store hash
                var hash = this.hash;

                // Using jQuery's animate() method to add smooth page scroll
                $("html, body").animate(
                    {
                        scrollTop: $(hash).offset().top,
                    },
                    800,
                    function () {
                        window.location.hash = hash;
                    }
                );
            } 
        });
    });
</script>

<!--JS for Owl tasty slider start-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    $(document).ready(function(){
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            autoplay: true, 
            autoplayTimeout: 4000,
            responsive:{
                0:{
                    items: 1
                },
                600:{
                    items: 2
                },
                1000:{
                    items: 3
                }
            }
        });
    });
</script>

<!--JS for Owl tasty slider end-->

</html>