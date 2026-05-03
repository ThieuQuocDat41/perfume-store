<nav class="fixed top-0 left-0 right-0 z-50 border-b bg-white bg-opacity-95 backdrop-blur px-10 py-3">

    <div class="max-w-[1440px] mx-auto flex items-center justify-between">

        <!-- LEFT: LOGO -->
        <a href="/products" class="text-xl font-serif tracking-[0.3em] uppercase text-[#d4af37]">
            TTRINHPERFUME
        </a>

        <!-- RIGHT: ACTIONS -->
        <div class="flex items-center gap-6">

        <!-- SEARCH BAR -->
        <form action="/products" method="GET" 
              class="flex items-center border border-gray-300 rounded-full px-4 h-10 
                     transition hover:border-black focus-within:border-[#d4af37]">

            <span class="material-symbols-outlined text-gray-400 mr-2">
                search
            </span>

            <input type="text" name="search"
                   placeholder="Search your perfumes..."
                   class="outline-none border-none focus:ring-0 w-48 text-sm placeholder-gray-400 bg-transparent">
        </form>


        <!-- CART -->
        <a href="/cart"
           class="relative text-black hover:text-[#d4af37] transition duration-300 hover:scale-110">

            <span class="material-symbols-outlined text-2xl">
                shopping_bag
            </span>
                @php
                    $cartCount = 0;
                    if (auth()->check()) {
                        $cart = \App\Models\Cart::where('user_id', auth()->id())->with('items')->first();
                        $cartCount = $cart ? $cart->items->sum('quantity') : 0;
                    }
                @endphp

                <span id="cart-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1 rounded-full">
                    {{ $cartCount }}
                </span>
        </a>


        <!-- PROFILE DROPDOWN -->
        <div class="relative group">

            <!-- ICON -->
            <button class="text-black hover:text-[#d4af37] transition duration-300 hover:scale-110">
                <span class="material-symbols-outlined text-2xl">
                    person
                </span>
            </button>

            <!-- DROPDOWN -->
            <div class="absolute right-0 mt-3 w-40 bg-white shadow-xl rounded-lg 
                        opacity-0 invisible 
                        group-hover:opacity-100 group-hover:visible 
                        transition-all duration-300 z-50">

                <a href="/profile"
                   class="block px-4 py-2 hover:bg-gray-100 text-sm">
                    Profile
                </a>

                @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="/admin"
                   class="block px-4 py-2 hover:bg-gray-100 text-sm">
                    Dashboard
                </a>
                @endif

                <form method="POST" action="/logout">
                    @csrf
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm">
                        Logout
                    </button>
                </form>

            </div>

        </div>

        </div>

    </div>

</nav>