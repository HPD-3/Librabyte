<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Details</title>
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .info img {
            width: 150px;
            height: auto;
            float: left;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        .info {
            overflow: hidden;
            background-color: #25475c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            border: 1px #25475c solid;
        }
        .button {
            display: block;
            color: white;
            margin: 20px;
            text-decoration: none;
            background-color: #53786d;
            padding: 10px 20px;
            border-radius: 20px;
            width: fit-content;
        }
        .button:hover {
            background-color: #5cd1ae;
        }
        .meta {
            margin-top: 10px;
            padding: 10px;
            background-color: #25475c;
            color: white;
            border-radius: 5px;
            border: 1px #25475c solid;
        }
        .meta a {
            color: #5cd1ae;
        }
    </style>
    <link rel="stylesheet" href="assets/dashstyle.css">
</head>
<body>
<script src="scripts/dashboard.js"></script>
<?php include('navbar/dashnav.php'); ?>

<a href="javascript:window.history.back()" class="button">&larr; Back</a>

<div class="container">
    <div id="bookDetail">Loading book details...</div>
</div>

<script>
    async function loadBookDetail() {
        const params = new URLSearchParams(window.location.search);
        const bookId = params.get('id');
        if (!bookId) {
            document.getElementById('bookDetail').innerText = 'Book ID not provided.';
            return;
        }

        try {
            const res = await fetch(`https://www.googleapis.com/books/v1/volumes/${bookId}`);
            const data = await res.json();

            const info = data.volumeInfo;
            const sale = data.saleInfo;
            const access = data.accessInfo;

            const title = info.title || 'Untitled';
            const subtitle = info.subtitle || '';
            const authors = info.authors?.join(', ') || 'Unknown';
            const rating = info.averageRating ? `${info.averageRating} / 5` : 'No rating';
            const categories = info.categories?.join(', ') || 'Uncategorized';
            const publisher = info.publisher || 'Unknown';
            const publishedDate = info.publishedDate || 'Unknown';
            const description = info.description || 'No description available.';
            const thumbnail = info.imageLinks?.thumbnail || 'https://via.placeholder.com/150';
            const pageCount = info.pageCount || 'N/A';
            const language = info.language || 'Unknown';
            const isbnList = info.industryIdentifiers?.map(i => `${i.type}: ${i.identifier}`).join('<br>') || 'N/A';

            const previewLink = info.previewLink || '#';
            const infoLink = info.infoLink || '#';
            const webReaderLink = access.webReaderLink || '#';

            document.getElementById('bookDetail').innerHTML = `
                <div class="info">
                    <img src="${thumbnail}" alt="Book Cover">
                    <h2>${title}</h2>
                    ${subtitle ? `<h3>${subtitle}</h3>` : ''}
                    <p><strong>Authors:</strong> ${authors}</p>
                    <p><strong>Rating:</strong> ${rating}</p>
                    <p><strong>Categories:</strong> ${categories}</p>
                    <p><strong>Publisher:</strong> ${publisher}</p>
                    <p><strong>Published Date:</strong> ${publishedDate}</p>
                    <p><strong>Page Count:</strong> ${pageCount}</p>
                    <p><strong>Language:</strong> ${language}</p>
                    <p><strong>ISBNs:</strong><br>${isbnList}</p>
                </div>
                <div class="meta">
                    <h3>Description:</h3>
                    <p>${description}</p>
                    <h3>Links:</h3>
                    <ul>
                        <li><a href="${previewLink}" target="_blank">Preview on Google Books</a></li>
                        <li><a href="${infoLink}" target="_blank">More Info</a></li>
                        <li><a href="${webReaderLink}" target="_blank">Web Reader</a></li>
                    </ul>
                    <p><strong>eBook Available:</strong> ${sale.isEbook ? 'Yes' : 'No'}</p>
                    <p><strong>Sale Status:</strong> ${sale.saleability}</p>
                    <p><strong>Viewability:</strong> ${access.viewability}</p>
                </div>
            `;
        } catch (error) {
            console.error('Error fetching book:', error);
            document.getElementById('bookDetail').innerText = 'Failed to load book data.';
        }
    }

    window.addEventListener('DOMContentLoaded', loadBookDetail);
</script>
</body>
</html>
