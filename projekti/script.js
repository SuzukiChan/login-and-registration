const form = document.querySelector('form');
form.addEventListener('submit', (e) => {
  e.preventDefault();

  grecaptcha.ready(function() {
    grecaptcha.execute('YOUR_CAPTCHA_KEY', { action: 'submit' }).then(function(token) {
      // Add the reCAPTCHA token to the form data
      const fd = new FormData(form);
      fd.append('g-recaptcha-response', token);

      // Send the form data with the reCAPTCHA token to the server
      fetch('process_form.php', {
        method: "POST",
        body: fd
      })
      .then(res => res.json())
      .then(data => console.log(data))
      .catch(err => console.error(err));
    });
  });
});
