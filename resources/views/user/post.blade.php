@extends('layout.app')

@section('content')

{{-- Hiển thị thông báo --}}
@if(session('success'))
<div class="custom-alert custom-alert-success" id="alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="custom-alert custom-alert-error" id="alert-error">
    <i class="fas fa-times-circle"></i> {{ session('error') }}
</div>
@endif

{{-- Hiển thị lỗi validation --}}
@if($errors->any())
<div class="custom-alert custom-alert-error" id="alert-validate">
    <i class="fas fa-exclamation-triangle"></i>
    <ul style="margin: 0; padding-left: 20px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        line-height: 1.6;
    }

    /* Header */
    .header {
        background: linear-gradient(135deg, #003580, #0071c2);
        color: white;
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-size: 1.8rem;
        font-weight: bold;
        text-decoration: none;
        color: white;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 2rem;
    }

    .nav-menu a {
        color: white;
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        transition: background-color 0.3s;
    }

    .nav-menu a:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    /* Main Container */
    .container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    /* Main Content */
    .main-content {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, #003580, #0071c2);
        color: white;
        padding: 1rem 1.5rem;
        font-size: 1.3rem;
        font-weight: bold;
    }

    /* Featured Article */
    .featured-article {
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .featured-article img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .featured-article h2 {
        font-size: 1.8rem;
        color: #003580;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .featured-article h2:hover {
        color: #0071c2;
        cursor: pointer;
    }

    .article-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #666;
    }

    .article-meta span {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .article-excerpt {
        color: #333;
        line-height: 1.6;
    }

    /* Article List */
    .article-list {
        padding: 1.5rem;
    }

    .article-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.3s;
    }

    .article-item:hover {
        background-color: #f8f9fa;
    }

    .article-item:last-child {
        border-bottom: none;
    }

    .article-thumbnail {
        width: 150px;
        height: 100px;
        border-radius: 6px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .article-content {
        flex: 1;
    }

    .article-title {
        font-size: 1.1rem;
        color: #003580;
        font-weight: 600;
        margin-bottom: 0.5rem;
        line-height: 1.4;
        cursor: pointer;
    }

    .article-title:hover {
        color: #0071c2;
    }

    .article-summary {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 0.5rem;
    }

    .article-info {
        display: flex;
        gap: 1rem;
        font-size: 0.8rem;
        color: #888;
    }

    /* Sidebar */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .sidebar-widget {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .widget-header {
        background: linear-gradient(135deg, #003580, #0071c2);
        color: white;
        padding: 1rem;
        font-weight: bold;
    }

    .widget-content {
        padding: 1rem;
    }

    /* Categories */
    .category-list {
        list-style: none;
    }

    .category-list li {
        padding: 0.7rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .category-list li:last-child {
        border-bottom: none;
    }

    .category-list a {
        color: #333;
        text-decoration: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: color 0.3s;
    }

    .category-list a:hover {
        color: #0071c2;
    }

    .category-count {
        background: #e8f4fd;
        color: #0071c2;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.8rem;
    }

    /* Recent Posts */
    .recent-post {
        display: flex;
        gap: 0.8rem;
        padding: 0.8rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .recent-post:last-child {
        border-bottom: none;
    }

    .recent-thumbnail {
        width: 60px;
        height: 60px;
        border-radius: 4px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .recent-content h4 {
        font-size: 0.9rem;
        color: #003580;
        line-height: 1.3;
        margin-bottom: 0.3rem;
        cursor: pointer;
    }

    .recent-content h4:hover {
        color: #0071c2;
    }

    .recent-date {
        font-size: 0.8rem;
        color: #888;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-published {
        background: #d4edda;
        color: #155724;
    }

    .status-draft {
        background: #f8d7da;
        color: #721c24;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            grid-template-columns: 1fr;
            margin: 1rem auto;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
        }

        .nav-menu {
            gap: 1rem;
        }

        .article-item {
            flex-direction: column;
        }

        .article-thumbnail {
            width: 100%;
            height: 200px;
        }

        .featured-article img {
            height: 200px;
        }
    }

    /* Loading Animation */
    .loading {
        text-align: center;
        padding: 2rem;
        color: #666;
    }

    .loading i {
        font-size: 2rem;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
</head>

<body>
    <!-- Header -->


    <!-- Main Container -->
    <div class="container">
        <!-- Main Content -->
        <main class="main-content">
            <div class="section-header">
                <i class="fas fa-newspaper"></i> Tin tức mới nhất
            </div>

            @if($posts->count() > 0)
            <!-- Featured Article (First Post) -->
            @php $featuredPost = $posts->first(); @endphp
            <article class="featured-article">
                @if($featuredPost->images)
                <img src="{{ asset('storage/' . $featuredPost->images) }}"
                    alt="{{ $featuredPost->title }}"
                    onerror="this.src='https://via.placeholder.com/800x300/003580/ffffff?text=Không+có+ảnh'">
                @else
                <img src="https://via.placeholder.com/800x300/003580/ffffff?text=Không+có+ảnh"
                    alt="Không có ảnh">
                @endif

                <h2>{{ $featuredPost->title }}</h2>

                <div class="article-meta">
                    <span>
                        <i class="fas fa-user"></i>
                        {{ $featuredPost->author }}
                    </span>
                    <span>
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($featuredPost->published_at)->format('d/m/Y') }}
                    </span>
                    <span>
                        <i class="fas fa-tag"></i>
                        {{ $featuredPost->category }}
                    </span>
                    <span class="status-badge {{ $featuredPost->status ? 'status-published' : 'status-draft' }}">
                        {{ $featuredPost->status ? 'Đã xuất bản' : 'Bản nháp' }}
                    </span>
                </div>

                <div class="article-excerpt">
                    {{ Str::limit(strip_tags($featuredPost->content), 200) }}
                </div>
            </article>

            <!-- Article List -->
            <div class="article-list">
                @foreach($posts->skip(1) as $post)
                <article class="article-item">
                    @if($post->images)
                    <img src="{{ asset('storage/' . $post->images) }}"
                        alt="{{ $post->title }}"
                        class="article-thumbnail"
                        onerror="this.src='https://via.placeholder.com/150x100/003580/ffffff?text=Không+có+ảnh'">
                    @else
                    <img src="https://via.placeholder.com/150x100/003580/ffffff?text=Không+có+ảnh"
                        alt="Không có ảnh"
                        class="article-thumbnail">
                    @endif
                    <div class="article-content">
                        <h3 class="article-title">{{ $post->title }}</h3>
                        <p class="article-summary">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>
                        <div class="article-info">
                            <span>
                                <i class="fas fa-user"></i>
                                {{ $post->author }}
                            </span>
                            <span>
                                <i class="fas fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }}
                            </span>
                            <span>
                                <i class="fas fa-tag"></i>
                                {{ $post->category }}
                            </span>
                            <span class="status-badge {{ $post->status ? 'status-published' : 'status-draft' }}">
                                {{ $post->status ? 'Đã xuất bản' : 'Bản nháp' }}
                            </span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="loading">
                <i class="fas fa-spinner"></i>
                <p>Chưa có bài viết nào</p>
            </div>
            @endif
        </main>

        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Categories Widget -->
            <div class="sidebar-widget">
                <div class="widget-header">
                    <i class="fas fa-folder"></i> Danh mục
                </div>
                <div class="widget-content">
                    <ul class="category-list">
                        @php
                        $categories = $posts->pluck('category')->unique()->filter();
                        @endphp
                        @foreach($categories as $category)
                        <li>
                            <a href="#">
                                {{ $category }}
                                <span class="category-count">
                                    {{ $posts->where('category', $category)->count() }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                        @if($categories->isEmpty())
                        <li>Chưa có danh mục nào</li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Recent Posts Widget -->
            <div class="sidebar-widget">
                <div class="widget-header">
                    <i class="fas fa-clock"></i> Bài viết gần đây
                </div>
                <div class="widget-content">
                    @foreach($posts->take(5) as $recentPost)
                    <div class="recent-post">
                        @if($recentPost->images)
                        <img src="{{ asset('storage/' . $recentPost->images) }}"
                            alt="{{ $recentPost->title }}"
                            class="recent-thumbnail"
                            onerror="this.src='https://via.placeholder.com/60x60/003580/ffffff?text=No+Image'">
                        @else
                        <img src="https://via.placeholder.com/60x60/003580/ffffff?text=No+Image"
                            alt="Không có ảnh"
                            class="recent-thumbnail">
                        @endif

                        <div class="recent-content">
                            <h4>{{ Str::limit($recentPost->title, 50) }}</h4>
                            <div class="recent-date">
                                {{ \Carbon\Carbon::parse($recentPost->published_at)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Authors Widget -->
            <div class="sidebar-widget">
                <div class="widget-header">
                    <i class="fas fa-users"></i> Tác giả
                </div>
                <div class="widget-content">
                    <ul class="category-list">
                        @php
                        $authors = $posts->pluck('author')->unique()->filter();
                        @endphp
                        @foreach($authors as $author)
                        <li>
                            <a href="#">
                                <i class="fas fa-user"></i> {{ $author }}
                                <span class="category-count">
                                    {{ $posts->where('author', $author)->count() }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                        @if($authors->isEmpty())
                        <li>Chưa có tác giả nào</li>
                        @endif
                    </ul>
                </div>
            </div>
        </aside>
    </div>

    <script>
        // Lazy loading images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', function() {
                    this.style.opacity = '1';
                });
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

    </html>

    @endsection