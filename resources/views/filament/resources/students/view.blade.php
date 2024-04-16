<x-filament-panels::page>
<h2>Detalhe do Estudante</h2>
@if ($this->hasInfolist())
    {{$this->infolist}}
@else
    {{$this->form}}
@endif

</x-filament-panels::page>
