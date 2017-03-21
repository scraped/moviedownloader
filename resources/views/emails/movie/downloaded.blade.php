@if ($isSubtitleFound)
    <p>O filme <strong>{{ $movie }}</strong> foi baixado com sucesso e já está disponível para ser assistido.</p>
@else
    <p>O filme <strong>{{ $movie }}</strong> foi baixado com sucesso mas infelizmente não achamos a legenda, ela precisará ser <strong>baixada manualmente</strong>.</p>
@endif

<p>Divirta-se !!</p>