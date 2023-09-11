<?php
require 'config.php';
if (!isset($_SESSION["user_id"])) {
  header("Location: admin-login.php");
  exit();
}
$conn=connectDB();
$id=$_SESSION["user_id"];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user_id = $_POST["user_id"];
  $index = $_POST["index"];
  $sql = "INSERT INTO user_subscriptions (user_id, plan_id) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error in preparing the statement: " . $conn->error);
}

// Bind the parameters and their data types
// "si" indicates that the first parameter is a string and the second is an integer
$stmt->bind_param("si", $id, $index);

// Execute the prepared statement
if ($stmt->execute()) {
    header("Location: file.php");
    echo "Data inserted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
  <div class="px-6 pt-6 lg:px-8">
    <nav class="flex items-center justify-between" aria-label="Global">
      <div class="flex lg:flex-1">
        <a href="#" class="-m-1.5 p-1.5">
          <span class="sr-only">Your Company</span>
        </a>
      </div>
      <div class="flex lg:hidden">
        <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
          <span class="sr-only">Open main menu</span>
          <!-- Heroicon name: outline/bars-3 -->
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </button>
      </div>
      <div class="hidden lg:flex lg:gap-x-12">
        <a href="http://localhost/classification/product" class="text-sm font-semibold leading-6 text-gray-900">Product</a>

        <a href="http://localhost/classification/price.html" class="text-sm font-semibold leading-6 text-gray-900">Pricing</a>

        <a href="http://localhost/classification/signup.html" class="text-sm font-semibold leading-6 text-gray-900">Signup</a>

        <a href="http://localhost/classification/about.html" class="text-sm font-semibold leading-6 text-gray-900">About Us</a>
      </div>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Log in <span aria-hidden="true">&rarr;</span></a>
      </div>
    </nav>
<div class="mx-auto max-w-7xl bg-white py-24 px-6 lg:px-8">
    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-none lg:text-6xl">Pricing plans for teams of all sizes</h2>
    <p class="mt-6 max-w-2xl text-xl text-gray-500">Choose an affordable plan that's packed with the best features for engaging your audience, creating customer loyalty, and driving sales.</p>
  
    <!-- Tiers -->
    <div class="mt-24 space-y-12 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:space-y-0">
      <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
        <div class="flex-1">
          <h3 class="text-xl font-semibold text-gray-900">Freelancer</h3>
          <p class="mt-4 flex items-baseline text-gray-900">
            <span class="text-5xl font-bold tracking-tight">$24</span>
            <span class="ml-1 text-xl font-semibold">/month</span>
          </p>
          <p class="mt-6 text-gray-500">The essentials to provide your best work for clients.</p>
  
          <!-- Feature list -->
          <ul role="list" class="mt-6 space-y-6">
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">5 files</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">Up to 1MB files</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">Basic analytics</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">48-hour support response time</span>
            </li>
          </ul>
        </div>
        <a href="#" onclick="myFunction(1)" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Monthly billing</a>
      </div>
  
      <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
        <div class="flex-1">
          <h3 class="text-xl font-semibold text-gray-900">Startup</h3>
  
          <p class="absolute top-0 -translate-y-1/2 transform rounded-full bg-indigo-500 py-1.5 px-4 text-sm font-semibold text-white">Most popular</p>
          <p class="mt-4 flex items-baseline text-gray-900">
            <span class="text-5xl font-bold tracking-tight">$32</span>
            <span class="ml-1 text-xl font-semibold">/month</span>
          </p>
          <p class="mt-6 text-gray-500">A plan that scales with your rapidly growing business.</p>
  
          <!-- Feature list -->
          <ul role="list" class="mt-6 space-y-6">
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">25 files</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">Up to 1000MB files</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">Advanced analytics</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">24-hour support response time</span>
            </li>
          </ul>
        </div>
        <form action="" method="POST">
    <!-- Add other form fields here if needed -->
        <input type="hidden" name="user_id" value=<? echo $id;?>> <!-- Replace with user_id from your submission -->
        <input type="hidden" name="index" id="index_input">
        <button type="submit">Submit</button>
    </form>
  
        <a href="#" onclick="myFunction(2)" class="bg-indigo-500 text-white hover:bg-indigo-600 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Monthly billing</a>
      </div>
  
      <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
        <div class="flex-1">
          <h3 class="text-xl font-semibold text-gray-900">Enterprise</h3>
          <p class="mt-4 flex items-baseline text-gray-900">
            <span class="text-5xl font-bold tracking-tight">$48</span>
            <span class="ml-1 text-xl font-semibold">/month</span>
          </p>
          <p class="mt-6 text-gray-500">Dedicated support and infrastructure for your company.</p>
  
          <!-- Feature list -->
          <ul role="list" class="mt-6 space-y-6">
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">Unlimited files</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">Unlimited size of files</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">Advanced analytics</span>
            </li>
  
            <li class="flex">
              <!-- Heroicon name: outline/check -->
              <svg class="h-6 w-6 flex-shrink-0 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
              </svg>
              <span class="ml-3 text-gray-500">1-hour, dedicated support response time</span>
            </li>
          </ul>
        </div>
  
        <a href="#" onclick="myFunction(3)" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Monthly billing</a>
      </div>
    </div>
  </div>
  <script>
    function myFunction(val){
      document.getElementById("index_input").value = val;
      document.querySelector("form").submit();
    }
</script>
</body>
</html>
  