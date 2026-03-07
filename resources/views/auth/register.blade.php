<x-guest-layout title="Join EduCore">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Join EduCore</h2>
        <p class="mt-2 text-sm text-gray-500 font-medium pb-2 border-b border-gray-100">Choose your role to get started with a tailored experience.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="register-form" class="space-y-5">
        @csrf
        <input type="hidden" name="role" id="role-input" value="{{ old('role', 'student') }}" required />

        <!-- Role Selector -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="role-card group relative p-4 rounded-2xl border-2 {{ old('role') === 'teacher' ? 'border-purple-500 bg-purple-50/50 shadow-md ring-4 ring-purple-100' : 'border-gray-100 bg-white/50 hover:bg-gray-50 hover:border-purple-200 cursor-pointer transition-all' }}" id="card-teacher" onclick="selectRole('teacher')" role="button" tabindex="0">
                <div class="absolute top-3 right-3 w-4 h-4 rounded-full border-2 {{ old('role') === 'teacher' ? 'border-purple-500 bg-purple-500' : 'border-gray-300 group-hover:border-purple-300' }} transition-colors"></div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform shadow-sm">👩‍🏫</div>
                <div class="font-bold text-gray-900 {{ old('role') === 'teacher' ? 'text-purple-700' : '' }}">Teacher</div>
                <div class="text-[11px] text-gray-500 font-medium mt-1 leading-tight">Attendance, grades & alerts</div>
            </div>
            
            <div class="role-card group relative p-4 rounded-2xl border-2 {{ old('role', 'student') === 'student' ? 'border-indigo-500 bg-indigo-50/50 shadow-md ring-4 ring-indigo-100' : 'border-gray-100 bg-white/50 hover:bg-gray-50 hover:border-indigo-200 cursor-pointer transition-all' }}" id="card-student" onclick="selectRole('student')" role="button" tabindex="0">
                <div class="absolute top-3 right-3 w-4 h-4 rounded-full border-2 {{ old('role', 'student') === 'student' ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300 group-hover:border-indigo-300' }} transition-colors"></div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-2xl mb-3 group-hover:scale-110 transition-transform shadow-sm">🎓</div>
                <div class="font-bold text-gray-900 {{ old('role', 'student') === 'student' ? 'text-indigo-700' : '' }}">Student</div>
                <div class="text-[11px] text-gray-500 font-medium mt-1 leading-tight">Grades, schedule & tasks</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="first_name">First Name</label>
                <input id="first_name" type="text" name="first_name" class="w-full px-4 py-3 bg-white/50 border {{ $errors->has('first_name') ? 'border-red-300 ring-2 ring-red-100 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} rounded-xl shadow-sm transition-colors text-sm" placeholder="Jane" value="{{ old('first_name') }}" required autofocus autocomplete="given-name" />
                @error('first_name')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="last_name">Last Name</label>
                <input id="last_name" type="text" name="last_name" class="w-full px-4 py-3 bg-white/50 border {{ $errors->has('last_name') ? 'border-red-300 ring-2 ring-red-100 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} rounded-xl shadow-sm transition-colors text-sm" placeholder="Doe" value="{{ old('last_name') }}" required autocomplete="family-name" />
                @error('last_name')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="email">Email Address</label>
            <input id="email" type="email" name="email" class="w-full px-4 py-3 bg-white/50 border {{ $errors->has('email') ? 'border-red-300 ring-2 ring-red-100 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} rounded-xl shadow-sm transition-colors text-sm" placeholder="you@school.edu" value="{{ old('email') }}" required autocomplete="username" />
            @error('email')
                <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="phone">Phone Number</label>
            <input id="phone" type="tel" name="phone" class="w-full px-4 py-3 bg-white/50 border {{ $errors->has('phone') ? 'border-red-300 ring-2 ring-red-100 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} rounded-xl shadow-sm transition-colors text-sm" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}" autocomplete="tel" />
            @error('phone')
                <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="password">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" class="w-full px-4 py-3 bg-white/50 border {{ $errors->has('password') ? 'border-red-300 ring-2 ring-red-100 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} rounded-xl shadow-sm transition-colors text-sm pr-10" placeholder="Min 6 chars" minlength="6" required autocomplete="new-password" />
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 focus:outline-none" onclick="var i=document.getElementById('password');i.type=i.type==='password'?'text':'password';this.innerHTML=i.type==='password'?'<svg class=\'w-4 h-4\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\' /></svg>':'<svg class=\'w-4 h-4\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\' /></svg>'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                 </div>
                @error('password')
                    <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="w-full px-4 py-3 bg-white/50 border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-colors text-sm" placeholder="Repeat password" required autocomplete="new-password" />
            </div>
        </div>

        <!-- Password Meter -->
        <div id="pw-meter" class="mb-2 bg-gray-50/50 p-3 rounded-xl border border-gray-100" aria-live="polite">
            <div class="flex items-center justify-between text-xs font-bold text-gray-500 mb-2">
                <span>Strength</span>
                <span id="pw-strength-label" class="px-2 py-0.5 rounded text-[10px] uppercase tracking-wider bg-gray-200 text-gray-600">—</span>
            </div>
            <div class="w-full rounded-full overflow-hidden h-1.5 bg-gray-200 mb-2.5 shadow-inner">
                <div id="pw-strength-bar" class="h-full w-0 bg-gray-300 rounded-full transition-all duration-300 ease-out"></div>
            </div>
            <div class="flex flex-wrap gap-x-3 gap-y-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wide">
                <span id="pw-rule-length">6+ chars</span>
                <span id="pw-rule-lower">lowercase</span>
                <span id="pw-rule-upper">uppercase</span>
                <span id="pw-rule-number">number</span>
                <span id="pw-rule-symbol">symbol</span>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="security_question">Security Question <span class="text-gray-400 font-medium">(Optional)</span></label>
            <select id="security_question" name="security_question" class="w-full px-4 py-3 bg-white/50 border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm transition-colors text-sm text-gray-700">
                <option value="">Select a security question</option>
                <option value="What is the name of your first pet?" {{ old('security_question') === 'What is the name of your first pet?' ? 'selected' : '' }}>What is the name of your first pet?</option>
                <option value="What is your mother's maiden name?" {{ old('security_question') === "What is your mother's maiden name?" ? 'selected' : '' }}>What is your mother's maiden name?</option>
                <option value="What was the name of your first school?" {{ old('security_question') === 'What was the name of your first school?' ? 'selected' : '' }}>What was the name of your first school?</option>
                <option value="What city were you born in?" {{ old('security_question') === 'What city were you born in?' ? 'selected' : '' }}>What city were you born in?</option>
                <option value="What is your favourite childhood book?" {{ old('security_question') === 'What is your favourite childhood book?' ? 'selected' : '' }}>What is your favourite childhood book?</option>
            </select>
            @error('security_question')
                <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 truncate" for="security_answer">Security Answer <span class="text-gray-400 font-medium">(Optional)</span></label>
            <input id="security_answer" type="text" name="security_answer" class="w-full px-4 py-3 bg-white/50 border {{ $errors->has('security_answer') ? 'border-red-300 ring-2 ring-red-100 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-indigo-500 focus:ring-indigo-500' }} rounded-xl shadow-sm transition-colors text-sm" placeholder="Your answer (case-sensitive)" value="{{ old('security_answer') }}" autocomplete="off" />
            @error('security_answer')
                <p class="mt-1.5 text-xs font-bold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" id="submit-btn" class="w-full py-4 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold text-sm shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:shadow-[0_10px_25px_rgba(79,70,229,0.4)] hover:-translate-y-0.5 transition-all outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 tracking-wide mt-2">
            Create Account
        </button>

        <div class="text-center text-sm font-medium text-gray-600 mt-6 pt-6 border-t border-gray-100">
            Already have an account? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Sign In</a>
        </div>
    </form>

    @push('scripts')
    <script>
        // Custom Role Selector
        function selectRole(role) {
            document.getElementById('role-input').value = role;
            
            // Reset styles
            var tCard = document.getElementById('card-teacher');
            var sCard = document.getElementById('card-student');
            
            tCard.className = "role-card group relative p-4 rounded-2xl border-2 border-gray-100 bg-white/50 hover:bg-gray-50 hover:border-purple-200 cursor-pointer transition-all";
            tCard.querySelector('.absolute').className = "absolute top-3 right-3 w-4 h-4 rounded-full border-2 border-gray-300 group-hover:border-purple-300 transition-colors";
            tCard.querySelector('.font-bold').className = "font-bold text-gray-900";
            
            sCard.className = "role-card group relative p-4 rounded-2xl border-2 border-gray-100 bg-white/50 hover:bg-gray-50 hover:border-indigo-200 cursor-pointer transition-all";
            sCard.querySelector('.absolute').className = "absolute top-3 right-3 w-4 h-4 rounded-full border-2 border-gray-300 group-hover:border-indigo-300 transition-colors";
            sCard.querySelector('.font-bold').className = "font-bold text-gray-900";

            // Apply selected styles
            var activeCard = document.getElementById('card-' + role);
            if (role === 'teacher') {
                activeCard.className = "role-card group relative p-4 rounded-2xl border-2 border-purple-500 bg-purple-50/50 shadow-md ring-4 ring-purple-100 cursor-pointer transition-all";
                activeCard.querySelector('.absolute').className = "absolute top-3 right-3 w-4 h-4 rounded-full border-2 border-purple-500 bg-purple-500 transition-colors";
                activeCard.querySelector('.font-bold').className = "font-bold text-purple-700";
            } else {
                activeCard.className = "role-card group relative p-4 rounded-2xl border-2 border-indigo-500 bg-indigo-50/50 shadow-md ring-4 ring-indigo-100 cursor-pointer transition-all";
                activeCard.querySelector('.absolute').className = "absolute top-3 right-3 w-4 h-4 rounded-full border-2 border-indigo-500 bg-indigo-500 transition-colors";
                activeCard.querySelector('.font-bold').className = "font-bold text-indigo-700";
            }

            // Update button gradient based on role
            var btn = document.getElementById('submit-btn');
            if(role === 'teacher') {
                btn.className = "w-full py-4 px-4 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl font-bold text-sm shadow-[0_8px_20px_rgba(168,85,247,0.3)] hover:shadow-[0_10px_25px_rgba(168,85,247,0.4)] hover:-translate-y-0.5 transition-all outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 tracking-wide mt-2";
            } else {
                btn.className = "w-full py-4 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold text-sm shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:shadow-[0_10px_25px_rgba(79,70,229,0.4)] hover:-translate-y-0.5 transition-all outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 tracking-wide mt-2";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var r = document.getElementById('role-input').value;
            if (r) selectRole(r);

            // Password meter functionality
            var pw = document.getElementById('password');
            var bar = document.getElementById('pw-strength-bar');
            var label = document.getElementById('pw-strength-label');
            var rules = {
                length: document.getElementById('pw-rule-length'),
                lower: document.getElementById('pw-rule-lower'),
                upper: document.getElementById('pw-rule-upper'),
                number: document.getElementById('pw-rule-number'),
                symbol: document.getElementById('pw-rule-symbol'),
            };

            function setRule(el, ok) {
                if (!el) return;
                if(ok) {
                    el.className = "text-emerald-600";
                } else {
                    el.className = "text-gray-400";
                }
            }

            function calcStrength(value) {
                var v = value || '';
                var hasLower = /[a-z]/.test(v);
                var hasUpper = /[A-Z]/.test(v);
                var hasNumber = /[0-9]/.test(v);
                var hasSymbol = /[^A-Za-z0-9]/.test(v);
                var longEnough = v.length >= 6;

                setRule(rules.length, longEnough);
                setRule(rules.lower, hasLower);
                setRule(rules.upper, hasUpper);
                setRule(rules.number, hasNumber);
                setRule(rules.symbol, hasSymbol);

                var score = 0;
                if (longEnough) score++;
                if (hasLower) score++;
                if (hasUpper) score++;
                if (hasNumber) score++;
                if (hasSymbol) score++;

                if (v.length > 0 && v.length < 6) score = Math.min(score, 2);
                return score; 
            }

            function renderStrength() {
                if (!pw || !bar || !label) return;
                var score = calcStrength(pw.value);
                var pct = 0;
                var text = '—';
                var bgClass = 'bg-gray-300';
                var textClass = 'bg-gray-200 text-gray-600';

                if (!pw.value) {
                    pct = 0;
                } else if (score <= 2) {
                    pct = 33;
                    text = 'Weak';
                    bgClass = 'bg-red-500';
                    textClass = 'bg-red-100 text-red-700';
                } else if (score <= 4) {
                    pct = 66;
                    text = 'Good';
                    bgClass = 'bg-amber-500';
                    textClass = 'bg-amber-100 text-amber-700';
                } else {
                    pct = 100;
                    text = 'Strong';
                    bgClass = 'bg-emerald-500';
                    textClass = 'bg-emerald-100 text-emerald-700';
                }

                bar.style.width = pct + '%';
                bar.className = 'h-full ' + bgClass + ' rounded-full transition-all duration-300 ease-out';
                
                label.textContent = text;
                label.className = 'px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-bold transition-colors ' + textClass;
            }

            if (pw) {
                pw.addEventListener('input', renderStrength);
                renderStrength();
            }
        });
    </script>
    @endpush
</x-guest-layout>
