@props([
    'user',
    'size' => 10, // tailwind size (e.g. 10 => w-10 h-10)
    'font' => 'base', // tailwind font size
    'class' => '',
    'cacheBuster' => ''
])
@php
    use Illuminate\Support\Str;
    $photoUrl = $user->photo_url ?? '';
    $defaultAvatars = [
        asset('images/default-avatar.png'),
        asset('storage/avatars/default.png'),
        '/images/default-avatar.png',
        '/storage/avatars/default.png',
    ];
    $isDefault = in_array($photoUrl, $defaultAvatars) || Str::endsWith($photoUrl, '/images/default-avatar.png') || Str::endsWith($photoUrl, '/avatars/default.png');
    $colors = [
        'bg-blue-500', 'bg-green-500', 'bg-pink-500', 'bg-purple-500', 'bg-yellow-500', 'bg-red-500', 'bg-teal-500', 'bg-indigo-500', 'bg-orange-500', 'bg-cyan-500',
        'bg-blue-600', 'bg-green-600', 'bg-pink-600', 'bg-purple-600', 'bg-yellow-600', 'bg-red-600', 'bg-teal-600', 'bg-indigo-600', 'bg-orange-600', 'bg-cyan-600',
        'bg-fuchsia-500', 'bg-lime-500', 'bg-amber-500', 'bg-emerald-500', 'bg-rose-500', 'bg-violet-500', 'bg-sky-500', 'bg-slate-500', 'bg-stone-500', 'bg-neutral-500',
        'bg-fuchsia-600', 'bg-lime-600', 'bg-amber-600', 'bg-emerald-600', 'bg-rose-600', 'bg-violet-600', 'bg-sky-600', 'bg-slate-600', 'bg-stone-600', 'bg-neutral-600',
    ];
    $hashSource = $user->email ?? $user->id ?? Str::random();
    $hash = hexdec(substr(md5($hashSource), 0, 8));
    $color = $colors[$hash % count($colors)];
    $prenom = $user->prenom ?? '';
    $nom = $user->nom ?? '';
    $initials = strtoupper(Str::substr($prenom,0,1) . Str::substr($nom,0,1));
    if (empty(trim($initials))) $initials = 'U';
    $cacheBuster = $cacheBuster ?? '';
@endphp
@if($photoUrl && !$isDefault)
    <img src="{{ $photoUrl }}@if($cacheBuster)?v={{ $cacheBuster }}@endif" alt="Avatar" class="w-{{$size}} h-{{$size}} rounded-full object-cover border border-gray-200 shadow-sm {{$class}}">
@else
    <div class="w-{{$size}} h-{{$size}} {{$color}} rounded-full flex items-center justify-center border border-gray-200 {{$class}}">
        <span class="text-white font-bold select-none text-{{$font}}">{{ $initials }}</span>
    </div>
@endif
