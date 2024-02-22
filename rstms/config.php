<?php  

        require('stripe-php-master/init.php');

        $publishableKey="pk_test_51Ng6x2FJeRLIWboacdQpUcwrXETauECsXheDLPU3IVR0oO2qKKajcjCscg3eIB7wzdP8Rjwdm4JkkyJCzrGj3MYq00YLjSywZA";
        $secretKey="sk_test_51Ng6x2FJeRLIWboaHSRNBfm8csMgfm6YcQOSYQvULsIn3wXA4Ts3J3iF6P3eBjMpWPjbASlU7dn5iGibBIEGMtPG00p7gfgWhp";
        \Stripe\Stripe::setApiKey($secretKey);

	

?>