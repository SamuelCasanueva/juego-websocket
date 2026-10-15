@if($cell['type'] === 'word')
    <span class="cell" data-type="word" data-word="{{ $cell['word'] }}" data-index="{{ $cell['word_index'] }}">{{ $cell['char'] }}</span>
@elseif($cell['type'] === 'bracket')
    <span class="cell" data-type="bracket" data-bracket-id="{{ $cell['bracket_id'] }}" data-bonus="{{ $cell['bonus'] }}">{{ $cell['char'] }}</span>
@else
    <span class="cell" data-type="junk">{{ $cell['char'] }}</span>
@endif
