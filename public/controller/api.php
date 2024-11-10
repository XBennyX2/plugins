// In your theme's functions.php or a custom plugin
function sentiment_analysis_shortcode() {
    ob_start(); ?>
    <form id="sentimentForm">
        <textarea name="comment" id="comment" rows="4" placeholder="Enter your comment"></textarea>
        <button type="submit">Analyze Sentiment</button>
    </form>
    <div id="result"></div>

    <script>
        document.getElementById('sentimentForm').onsubmit = function(event) {
            event.preventDefault();
            const comment = document.getElementById('comment').value;

            fetch('http://flask:5000/predict-sentiment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ text: comment }),
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('result');
                if (data.error) {
                    resultDiv.innerHTML = 'Error: ' + data.error;
                } else {
                    resultDiv.innerHTML = 'Sentiment: ' + data.sentiment + ' (Confidence: ' + data.confidence.toFixed(2) + ')';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        };
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('sentiment_analysis', 'sentiment_analysis_shortcode');