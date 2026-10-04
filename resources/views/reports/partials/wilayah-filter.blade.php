{{--
    Dua field filter wilayah (Provinsi > Kabupaten), langsung selaras dengan
    field filter lain di dalam grid. Hanya dirender untuk Admin Pusat.
    Memerlukan $provinsis & $kabupatens dari trait FiltersWilayah.
--}}
@php
    $allKabupatenOptions = $kabupatens->map(fn ($k) => ['id' => $k->id, 'nama' => $k->nama])->values();
@endphp

<div>
    <label for="filter-provinsi" class="block text-xs font-medium text-slate-500 mb-1">Provinsi</label>
    <select name="provinsi_id" id="filter-provinsi" class="select2-provinsi block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
        <option value="">Semua Provinsi</option>
        @foreach($provinsis as $provinsi)
            <option value="{{ $provinsi->id }}" {{ (int) request('provinsi_id') === $provinsi->id ? 'selected' : '' }}>
                {{ $provinsi->nama }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="filter-kabupaten" class="block text-xs font-medium text-slate-500 mb-1">Kabupaten</label>
    <select name="kabupaten_id" id="filter-kabupaten" class="select2-kabupaten block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5 border">
        <option value="">Semua Kabupaten</option>
        @foreach($kabupatens as $kab)
            <option value="{{ $kab->id }}" {{ (int) request('kabupaten_id') === $kab->id ? 'selected' : '' }}>
                {{ $kab->nama }}
            </option>
        @endforeach
    </select>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        var $provinsi = $('#filter-provinsi');
        var $kabupaten = $('#filter-kabupaten');
        var allKabupaten = @json($allKabupatenOptions);
        var selectedKabupaten = "{{ request('kabupaten_id') ?: '' }}";

        $provinsi.select2({ width: '100%' });
        $kabupaten.select2({ width: '100%' });

        function replaceKabupaten(list, selected) {
            $kabupaten.empty().append('<option value="">Semua Kabupaten</option>');
            (list || []).forEach(function (item) {
                $kabupaten.append($('<option>', { value: item.id, text: item.nama }));
            });
            if (selected) {
                $kabupaten.val(String(selected)).trigger('change');
            }
        }

        $provinsi.on('change', function () {
            var provId = $(this).val();

            if (!provId) {
                replaceKabupaten(allKabupaten, selectedKabupaten);
                return;
            }

            // __PROVINSI__ adalah placeholder yang diganti dengan id provinsi terpilih.
            var urlTemplate = @json(route('api.kabupatens.by-provinsi', ['provinsi_id' => '__PROVINSI__']));

            $.get(urlTemplate.replace('__PROVINSI__', provId))
                .done(function (data) {
                    replaceKabupaten(data, selectedKabupaten);
                })
                .fail(function () {
                    replaceKabupaten([]);
                });
        });
    });
</script>
@endpush