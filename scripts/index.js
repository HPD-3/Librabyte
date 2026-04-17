function getRandomBooks(items, count = 8) {
    const shuffled = [...items].sort(() => 0.5 - Math.random());
    return shuffled.slice(0, count);
}

async function searchBooks(query = 'library', redirect = false) {
    if (!query.trim()) {
        alert('Please enter a search term.');
        return;
    }

    if (redirect) {
        window.location.href = 'dashboard.php?query=' + encodeURIComponent(query);
        return;
    }

    try {
        const randomContainer = document.getElementById('random');
        const output = document.getElementById('results');
        const res = await fetch('api/fetch_books.php?query=' + encodeURIComponent(query));
        const data = await res.json();

        if (!randomContainer || !output) {
            console.error("Missing containers #random or #results in HTML.");
            return;
        }

        if (!res.ok) {
            throw new Error(data.error || 'Book service request failed.');
        }

        randomContainer.innerHTML = '';
        output.innerHTML = '';

        if (data.error) {
            output.innerHTML = data.error;
            return;
        }

        if (data.items && data.items.length > 0) {
            const randomBooks = getRandomBooks(data.items, 8);

            randomBooks.forEach(book => {
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
                        <img src="${thumbnail}" alt="Book Cover">
                        <div class="book-details">
                            <h4>${info.title}</h4>
                            <h3>Rate :${rating}<h4>
                            <p>${authors}</p>
                        </div>
                    </div>
                `;

                randomContainer.appendChild(div);
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
    searchBooks('gamble');
});

function toggleMenu() {
    document.getElementById('navLinks').classList.toggle('show');
    document.getElementById('navAuth').classList.toggle('show');
}