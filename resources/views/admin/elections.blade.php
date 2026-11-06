<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TEST</title>
</head>
<body>
    <h2>Election</h2>
    <form method="POST" action="/create_election">
        @csrf
        <label for="election_name">Election Name:</label>
        <input type="text" id="election_name" name="election_name" required>
        
        <p>Results available?</p>
        <input type="radio" id="results_available_yes" name="results_available" value="yes">
        <label for="results_available_yes">Yes</label>

        <input type="radio" id="results_available_no" name="results_available" value="no">
        <label for="results_available_no">No</label>

        <p>Ballots enabled?</p>
        <input type="radio" id="ballots_enabled_yes" name="ballots_enabled" value="yes">
        <label for="ballots_enabled_yes">Yes</label>

        <input type="radio" id="ballots_enabled_no" name="ballots_enabled" value="no">
        <label for="ballots_enabled_no">No</label>
        <br>
        <button>Create Election</button>
    </form>
</body>
</html>