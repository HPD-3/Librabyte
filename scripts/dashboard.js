async function searchBooks(query = null, redirect = false) {
    if (!query) {
        const activeInput = window.innerWidth <= 768
            ? document.getElementById('mobile-search-bar')
            : document.getElementById('desktop-search-bar');

        if (!activeInput) {
            alert('Search input not found.');
            return;
        }

        query = activeInput.value.trim();
    }

    if (!query) {
        alert('Please enter a search term.');
        return;
    }

    if (redirect) {
        window.location.href = 'dashboard.php?query=' + encodeURIComponent(query);
        return;
    }

    try {
        const output = document.getElementById('results');
        const res = await fetch('api/fetch_books.php?query=' + encodeURIComponent(query));
        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.error || 'Book service request failed.');
        }

        output.innerHTML = '';

        if (data.error) {
            output.innerHTML = data.error;
            return;
        }

        if (data.items && data.items.length > 0) {
            data.items.forEach(book => {
                const info = book.volumeInfo;
                const id = book.id;
                const thumbnail = info.imageLinks?.thumbnail || 'https://via.placeholder.com/100x150?text=No+Image';
                const rating = info.averageRating ? `${info.averageRating} / 5` : 'No rating';
                const authors = info.authors?.join(', ') || 'Unknown';

                const div = document.createElement('div');
                div.className = 'book';

                const bookData = {
                    book_id: id,
                    title: info.title,
                    authors,
                    thumbnail,
                    rating: info.averageRating || 0
                };

                div.innerHTML = `
                    <div class="book-card">
                        <a href="books.php?id=${id}">
                            <img src="${thumbnail}" alt="Book Cover">
                        </a>
                        <div class="book-details">
                            <h4><a href="books.php?id=${id}">${info.title}</a></h4>
                            <p><strong>Rating:</strong> ${rating}</p>
                            <p><strong>Author(s):</strong> ${authors}</p>
                            <button onclick='addFavorite(${JSON.stringify(bookData)})'>Add to Favorites</button>
                        </div>
                    </div>
                `;

                output.appendChild(div);
            });
        } else {
            output.innerHTML = 'No books found.';
        }
    } catch (error) {
        console.error('Error fetching books:', error);
        const output = document.getElementById('results');
        output.innerHTML = error.message || 'Error fetching data. Please try again.';
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const query = params.get('query');
    if (query) {
        const mobileInput = document.getElementById('mobile-search-bar');
        const desktopInput = document.getElementById('desktop-search-bar');
        if (mobileInput) mobileInput.value = query;
        if (desktopInput) desktopInput.value = query;

        searchBooks(query);
    }
});

function addFavorite(bookData) {
    fetch('api/favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(bookData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Book added to favorites!');
            } else {
                alert('Login to Favorite a book.');
            }
        })
        .catch(err => {
            console.error('Error adding favorite:', err);
            alert('Error saving favorite.');
        });
}

function toggleMenu() {
    document.getElementById('navLinks').classList.toggle('show');
}
