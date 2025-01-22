<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<footer>
  <div class="footer-row">
    <!-- Brand and Introduction -->
    <div class="footer-col brand-col">
      <h3 class="footer-brand">UrbanDwells</h3>
      <p class="footer-description">
        At UrbanDwells, we make finding your perfect home simple, reliable, and stress-free. 
        Whether you're searching for cozy apartments, spacious family homes, or luxurious villas, 
        our platform offers a wide range of rental options to suit every lifestyle and budget.
      </p>
    </div>

    <!-- Office Information -->
    <div class="footer-col office-col">
      <h3 class="footer-heading">Office</h3>
      <address>
        <p class="footer-address">Dhamboji Road</p>
        <p class="footer-address">Nepalgunj, Banke</p>
        <p class="footer-email">Email: <a href="mailto:urbandwells@gmail.com">urbandwells@gmail.com</a></p>
        <p class="footer-phone">Phone: <a href="tel:+91012348888">+97 - 912348888</a></p>
      </address>
    </div>

    <!-- Navigation Links -->
    <div class="footer-col links-col">
      <h3 class="footer-heading">Quick Links</h3>
      <ul class="footer-links">
        <li><a href="#">Home</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Features</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>

    <!-- Newsletter and Social Media -->
    <div class="footer-col newsletter-col">
      <h3 class="footer-heading">Newsletter</h3>
      <form action="#" method="post" class="footer-newsletter">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit" class="newsletter-btn">
          <i class="fas fa-paper-plane"></i>
        </button>
      </form>
      <div class="footer-social">
        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-pinterest"></i></a>
      </div>
    </div>
  </div>

  <hr class="footer-divider">
  <p class="footer-copyright">UrbanDwells © 2024 - All Rights Reserved</p>
</footer>
<style>
  /* Footer Styles */
footer {
  background-color: #222;
  color: #fff;
  padding: 40px 20px;
  font-family: Arial, sans-serif;
}

.footer-row {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: space-between;
}

.footer-col {
  flex: 1 1 220px;
  margin: 10px;
}

.footer-brand {
  font-size: 24px;
  font-weight: bold;
  color: #f9a826;
}

.footer-description {
  font-size: 14px;
  line-height: 1.6;
  color: #ddd;
}

.footer-heading {
  font-size: 18px;
  margin-bottom: 10px;
  color: #f9a826;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin-bottom: 8px;
}

.footer-links a {
  color: #ddd;
  text-decoration: none;
  transition: color 0.3s;
}

.footer-links a:hover {
  color: #f9a826;
}

.footer-address,
.footer-email,
.footer-phone {
  margin-bottom: 8px;
  font-size: 14px;
  color: #ddd;
}

.footer-email a,
.footer-phone a {
  color: #f9a826;
  text-decoration: none;
}

.footer-email a:hover,
.footer-phone a:hover {
  text-decoration: underline;
}

/* Newsletter Styling */
.footer-newsletter {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.footer-newsletter input {
  padding: 10px;
  flex: 1;
  border: none;
  border-radius: 5px;
}

.newsletter-btn {
  background-color: #f9a826;
  border: none;
  color: #fff;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
}

.newsletter-btn i {
  font-size: 16px;
}

.newsletter-btn:hover {
  background-color: #e0881c;
}

/* Social Icons */
.footer-social {
  margin-top: 15px;
}

.social-icon {
  margin-right: 10px;
  color: #ddd;
  font-size: 18px;
  transition: color 0.3s;
}

.social-icon:hover {
  color: #f9a826;
}

/* Divider and Footer Bottom */
.footer-divider {
  margin: 20px 0;
  border: 1px solid #444;
}

.footer-copyright {
  text-align: center;
  font-size: 14px;
  color: #ddd;
}

</style>