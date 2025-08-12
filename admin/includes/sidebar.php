<aside id="sidebar" class="bg-gray-800 text-white w-64 h-full fixed top-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 md:flex-shrink-0">
    <div class="p-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold">Admin</a>
        <button id="close-sidebar" class="md:hidden text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <nav>
        <ul>
            <li class="p-4 hover:bg-gray-700"><a href="index.php" class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                Dashboard
            </a></li>
            <li class="p-4 hover:bg-gray-700"><a href="submissions.php" class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Submissions
            </a></li>
            <li class="p-4 hover:bg-gray-700"><a href="projects.php" class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M5 21v-5a2 2 0 012-2h10a2 2 0 012 2v5m-4-4h.01"></path></svg>
                Projects
            </a></li>
            <li class="p-4 hover:bg-gray-700"><a href="testimonials.php" class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Testimonials
            </a></li>
            <li class="p-4 hover:bg-gray-700"><a href="users.php" class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-1a6 6 0 00-5.176-5.97M15 21v-1a6 6 0 00-9-5.197"></path></svg>
                Users
            </a></li>
            <li class="p-4 hover:bg-gray-700"><a href="team.php" class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.28-1.25-.743-1.672M17 20v-2a3 3 0 00-3-3H7a3 3 0 00-3 3v2m10 0v-2a3 3 0 00-3-3H7a3 3 0 00-3 3v2m10 0h-3.172a3 3 0 00-5.656 0H7m10 0H7m10 0v-2a3 3 0 00-3-3H7a3 3 0 00-3 3v2"></path></svg>
                Team
            </a></li>
            <li class="p-4 hover:bg-gray-700"><a href="logout.php" class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </a></li>
        </ul>
    </nav>
</aside>
<div class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow p-4 flex justify-between items-center md:hidden">
        <div class="flex items-center">
            <button id="open-sidebar" class="mr-4 text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <h1 class="text-2xl font-bold"></h1>
        </div>
        <div class="flex items-center">
            <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
        </div>
    </header>
    <header class="bg-white shadow p-4 justify-between items-center hidden md:flex">
        <h1 class="text-2xl font-bold"></h1>
        <div class="flex items-center">
            <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
        </div>
    </header>
    <main class="flex-1 p-6 overflow-y-auto">
