<div>
    <section class="hero hero--sm" style="--hero-bg: {{ $heroBackgroundUrl }}">
        <div class="container">
            <h1>{{ $page->title ?? 'Contact Us' }}</h1>
            <div class="prose lead" style="max-width:60ch">
                {!! $page->content ?? '<p>Have questions? We would love to hear from you.</p>' !!}
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container split" style="align-items:start">
            <div style="display:grid;gap:1.5rem">
                <ul class="contact-list">
                    @if ($address)
                        <li class="feature"><span class="ico"><x-site.icon name="map-pin" /></span><div><strong>Address</strong><br><span class="muted">{{ $address }}</span></div></li>
                    @endif
                    @if ($phone)
                        <li class="feature"><span class="ico"><x-site.icon name="phone" /></span><div><strong>Phone</strong><br><a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a></div></li>
                    @endif
                    @if ($email)
                        <li class="feature"><span class="ico"><x-site.icon name="mail" /></span><div><strong>Email</strong><br><a href="mailto:{{ $email }}">{{ $email }}</a></div></li>
                    @endif
                    @if ($whatsapp)
                        <li class="feature"><span class="ico"><x-site.icon name="message-circle" /></span><div><strong>WhatsApp</strong><br><a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer">Chat with us</a></div></li>
                    @endif
                </ul>

                @if (count($businessHours))
                    <div class="card card-body">
                        <h3><x-site.icon name="clock" :size="18" /> Business hours</h3>
                        <table class="hours">
                            @foreach ($businessHours as $day => $hours)
                                <tr>
                                    <td>{{ is_array($hours) ? ($hours['day'] ?? $day) : $day }}</td>
                                    <td>{{ is_array($hours) ? ($hours['hours'] ?? (($hours['open'] ?? '') . ' – ' . ($hours['close'] ?? ''))) : $hours }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
            </div>

            <div class="card card-body">
                <h2 style="font-size:1.4rem">Send us a message</h2>
                @livewire('website.contact-form-submission')
            </div>
        </div>
    </section>

    @if ($mapsUrl)
        <section class="section" style="padding-top:0">
            <div class="container">
                <iframe class="map" src="{{ $mapsUrl }}" title="Map" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            </div>
        </section>
    @endif
</div>
