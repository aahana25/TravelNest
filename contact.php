<link rel="stylesheet" href="style.css">

<section class="contact-section">
  <h1>Contact Us</h1>
  <p class="contact-subtitle">We’d love to hear from you! Fill out the form and we’ll get back to you soon.</p>

  <!-- Contact Form -->
  <div class="contact-form-box">
    <form action="send_email.php" method="post">
      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" required>

      <label for="email">Your Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>

      <label for="message">Your Message</label>
      <textarea id="message" name="message" rows="5" placeholder="Type your message here..." required></textarea>

      <button type="submit">Send Message</button>
    </form>
  </div>

  <!-- Map -->
  <div class="map-box">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3683.558273134118!2d72.57136217530553!3d22.59932097948845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e5b5c86c011b5%3A0x1b6a0e08c86c8c8a!2sYour%20Location!5e0!3m2!1sen!2sin!4v1691490000000" 
      width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
  </div>

  <!-- Contact Info -->
  <div class="contact-details">
    <p><strong>📞 Phone:</strong> +91 70961 54341</p>
    <p><strong>📧 Email:</strong> aahanabavale@gmail.com</p>
    <p><strong>📍 Address:</strong> Ahmedabad, Gujarat</p>
  </div>

  <br>
  <p><a href="main.php">← Back to Dashboard</a></p>
  <hr class="bottom-line">
</section>