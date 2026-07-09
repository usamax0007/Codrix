@if(session('success'))
    <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400 text-sm mb-6" role="alert">
        {{ session('success') }}
    </div>
@endif

<form action="{{ url('/contact') }}" method="POST" class="space-y-5">
    @csrf
    <div>
        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="xc-input" placeholder="John Doe">
        @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="xc-input" placeholder="john@company.com">
        @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="subject" class="block text-sm font-medium text-slate-300 mb-2">Subject</label>
        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required class="xc-input" placeholder="Project inquiry">
        @error('subject')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="message" class="block text-sm font-medium text-slate-300 mb-2">Message</label>
        <textarea id="message" name="message" rows="5" required class="xc-input resize-none" placeholder="Tell us about your project...">{{ old('message') }}</textarea>
        @error('message')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="xc-btn-primary w-full">Send Message</button>
</form>
