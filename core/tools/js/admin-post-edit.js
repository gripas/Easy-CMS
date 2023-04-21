

function loadPostForm(mode, postId = null) {
  const url = mode === 'edit' ? 'get_post.php?post_id=' + postId : 'get_post.php';
  fetch(url)
    .then(response => response.text())
    .then(html => {
      document.querySelector('#get-post-edit').innerHTML = html;
      initializePostForm(mode);
    })
    .catch(error => {
      console.error('Error loading post form:', error);
    });
}

function initializePostForm(mode) {
  const postForm = document.querySelector('#post-form');
  initializeTagSuggestions(); // Įdėkite šią eilutę čia

  if (postForm) {
    postForm.addEventListener('submit', function (event) {
      event.preventDefault();

      const formData = new FormData(event.target);
      const url = mode === 'edit' ? 'update_post.php' : 'add_post.php';

      fetch(url, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            window.location.href = 'posts.php';
          } else {
            alert(mode === 'edit' ? 'Klaida atnaujinant įrašą.' : 'Klaida pridedant įrašą.');
          }
        })
        .catch(error => {
          console.error('Klaida:', error);
          alert(mode === 'edit' ? 'Klaida atnaujinant įrašą.' : 'Klaida pridedant įrašą.');
        });
    });
  }

  function initializeTagSuggestions() {
    const postTagsInput = document.getElementById('post_tags');
    const tagSuggestions = document.getElementById('tag-suggestions');

    postTagsInput.addEventListener('input', function () {
      const inputValue = postTagsInput.value.trim();

      if (inputValue === '') {
        tagSuggestions.innerHTML = '';
        return;
      }

      // Siunčia užklausą į serverį žymių pasiūlymams gauti
      fetch('get_tag_suggestions.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'input=' + encodeURIComponent(inputValue)
      })
        .then(response => response.json())
        .then(data => {
          // Rodo žymių pasiūlymus tagSuggestions elemente
          let suggestionsHtml = '';
          for (const tag of data.tags) {
            suggestionsHtml += `<span class="badge badge-secondary">${tag}</span> `;
          }
          tagSuggestions.innerHTML = suggestionsHtml;
        })
        .catch(error => {
          console.error('Error fetching tag suggestions:', error);
        });
    });
  }


    const cancelPostBtn = document.querySelector('#cancel-post');
    if (cancelPostBtn) {
        cancelPostBtn.addEventListener('click', function () {
            window.location.href = 'posts.php';
        });
    }
}

  
  // Paspaudus ištrynimo mygtuką, atidaro patvirtinimo modalą
  $('button[data-bs-target="#deletePostModal"]').on('click', function () {
    const postId = $(this).data('post-id');
    $('#confirmDeleteModal').data('post-id', postId);
    $('#confirmDeleteModal').modal('show');
  });
  
  // Paspaudus patvirtinimo mygtuką, ištrina puslapį
  $('#confirm-delete-btn').on('click', function () {
    const postId = $('#confirmDeleteModal').data('post-id');
  
    // Siunčia POST užklausą į delete_post.php failą
    $.ajax({
      type: 'POST',
      url: 'delete_post.php',
      data: {
        action: 'delete_post',
        post_id: postId
      },
      success: function(response) {
        // Uždaro modalą ir peradresuoja į posts.php puslapį
        $('#confirmDeleteModal').modal('hide');
        window.location.href = 'posts.php';
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Rodo klaidos pranešimą
        console.error(textStatus, errorThrown);
      }
    });
  });
  