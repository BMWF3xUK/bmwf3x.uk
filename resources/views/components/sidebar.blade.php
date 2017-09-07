<ul class="list-group">
    <a href="{{ route("home") }}" class="list-group-item {{ request()->route()->getName() == "home" ? "active" : null }}">
        Dashboard
    </a>

    <a href="{{ route("donate") }}" class="list-group-item {{ request()->route()->getName() == "donate" ? "active" : null }}">
        Donate
    </a>

    <a href="{{ route("guides.index") }}" class="list-group-item {{ starts_with(request()->route()->getName(), "guides.") ? "active" : null }}">
        Guides
    </a>
</ul>
