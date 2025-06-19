document.querySelectorAll('.delete-form').forEach(form => {
  form.addEventListener('submit', function(e) {
      if (!confirm('Are you sure you want to delete this comment? This action cannot be undone.')) {
          e.preventDefault();
      }
  });
});