<?php
require 'config.php';

$user_id = $_SESSION["user_id"];
$conn = connectDB();
$arr=[];
$check=FALSE;
// Query to count the number of uploaded files by the user
$query1 = "SELECT COUNT(*) AS file_count FROM uploaded_files WHERE user_id = ?";
$file_count = 0;

// Create a prepared statement
if ($stmt = $conn->prepare($query1)) {
    // Bind the user_id parameter
    $stmt->bind_param("i", $user_id); // "i" represents an integer parameter

    // Execute the query
    $stmt->execute();

    // Bind the result to a variable
    $stmt->bind_result($file_count);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();
}
$last_id=0;
$maxUploadLimit = 0;

// Query to retrieve user's subscription information
$subscriptionQuery = "SELECT sp.price, sp.files 
                      FROM user_subscriptions us
                      INNER JOIN plans sp ON us.plan_id = sp.id 
                      WHERE us.user_id = ?";

// Create a prepared statement for the subscription query
if ($stmt = $conn->prepare($subscriptionQuery)) {
    // Bind the user_id parameter
    $stmt->bind_param("i", $user_id); // "i" indicates that $user_id is an integer

    // Execute the query
    $stmt->execute();

    // Bind the result variables
    $stmt->bind_result($planName, $maxUploadLimit);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();
}

// Check if the form was submitted and files were uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["files"])) {
    // Count the number of uploaded files
    $check = TRUE;
    $fileCount = count($_FILES['files']['name']);
    $uploadDirectory = "uploads/";

    if ($fileCount + $file_count > $maxUploadLimit) {
        // Display a message indicating that the upload limit is reached
        echo "You have reached your maximum file upload limit of $maxUploadLimit files.";
    } else {
        // Create the directory if it doesn't exist
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Loop through each uploaded file
        foreach ($_FILES["files"]["name"] as $key => $fileName) {
            $tmpFilePath = $_FILES["files"]["tmp_name"][$key];

            // Check if the file is a text file (you specified .txt in the accept attribute)
            if (pathinfo($fileName, PATHINFO_EXTENSION) == "txt") {
                // Move the uploaded file to the upload directory
                $newFilePath = $uploadDirectory . $fileName;
                move_uploaded_file($tmpFilePath, $newFilePath);

                // Extract text from the text file (you may use a library like Tika or perform custom text extraction)
                $fileContent = file_get_contents($newFilePath);
                array_push($arr,$fileContent);
                $insertQuery = "INSERT INTO uploaded_files (user_id, file_name) VALUES (?, ?)";

                if ($stmt = $conn->prepare($insertQuery)) {
                    $stmt->bind_param("is", $user_id, $fileName);
                    $stmt->execute();
                    //fetch id of last inserted row
                    $last_id = $conn->insert_id;
                    $stmt->close();
                }
    


            } else {
                echo "File '$fileName' is not a valid text file.<br>";
            }
        }
    }
$data_json = json_encode($arr);

$headers = array(
    'Content-Type: application/json',
);
$url='http://127.0.0.1:5000/predict';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {

}

curl_close($ch);

} else {
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multiple Text File Upload</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<?php include 'navbar.php';?>
<body class="bg-gray-100 p-4 px-24">
    <div class="container-md mx-auto bg-white p-6 rounded-md shadow-md">
        <h1 class="text-2xl font-semibold mb-4">Upload Multiple Text Files</h1>
        <form action="" method="post" enctype="multipart/form-data">

            <div class="sm:col-span-6">
          <label for="cover-photo" class="block text-sm font-medium text-gray-700">Cover photo</label>
          <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-40 pb-6">
            <div class="space-y-1 text-center">
              <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <div class="flex text-sm text-gray-600">
                <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                  <span>Upload a file</span>
                  <input id="file-upload" type="file" name="files[]" accept=".txt" class="sr-only">
                </label>
                <p class="pl-1">or drag and drop</p>
              </div>
              <p class="text-xs text-gray-500">Txt Files Only</p>
            </div>
          </div>
        </div>
      </div>
      <br>
            <div class="mb-4">
                <input type="submit" value="Upload and Extract Text" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md cursor-pointer">
            </div>
        </form>
        <br>
        <br>
        <?php
        if ($check==TRUE){
        // Remove square brackets [] and single quotes ''
        $cleaned_string = str_replace(['[', ']', '"'], '', $response);
        $array_of_substrings = explode(",", $cleaned_string);
        $array_of_substrings = array_map('trim', $array_of_substrings);
        $i=0;
        foreach ($array_of_substrings as $substring) {
          echo 'Your file: ' . $_FILES['files']['name'][$i] . ' is classified as: ' . $substring . '<br>';
          //make new directory using id and then inside that directory make new directory for each class and move file according to class
          $dir = $last_id;
          if (!is_dir($dir)) {
              mkdir($dir, 0777, true);
          }
          $dir = $dir.'/'.$substring;
          if (!is_dir($dir)) {
              mkdir($dir, 0777, true);
          }
          $file = $_FILES['files']['name'][$i];
          $source = 'uploads/'.$file;
          $destination = $dir.'/'.$file;
          rename($source,$destination);          
          $i++;

      }
      //generate download link for last_id directory
      $dir = $last_id;
      $zip_file = $dir.'.zip';
      $zip = new ZipArchive;
      $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
      $files = new RecursiveIteratorIterator(
          new RecursiveDirectoryIterator($dir),
          RecursiveIteratorIterator::LEAVES_ONLY
      );
      foreach ($files as $name => $file)
      {
          // Skip directories (they would be added automatically)
          if (!$file->isDir())
          {
              // Get real and relative path for current file
              $filePath = $file->getRealPath();
              $relativePath = substr($filePath, strlen($dir) + 1);

              // Add current file to archive
              $zip->addFile($filePath, $relativePath);
          }
      }
      $zip->close();
      echo '<label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">';
      echo '<a href="'.$zip_file.'" download>Download All Files</a>';
      echo '</label>';

    }
        ?>
    </div>
</body>
</html>
