@include('layout.sidebar')
@include('layout.header')

<table class="table">
  <thead class="table-secondary">
  <tr>
    <th>Tananyag azonosítója</th>
    <th>Tananyag megnevezése</th>
    <th>Tananyag</th>
    <th>Kurzus neve</th>
    @if($isAdmin || $isTeacher)
    <th>Műveletek</th>
    @endif
    <th>Tananyag</th>

  </tr>
</thead>
<tbody>
  @foreach ($items as $item)
  @if (($item -> deleted_at) == NULL)
  <tr>
    <td>{{$item -> id}}</td>
    <td>{{$item -> topic}}</td>
    <td>{!! substr(strip_tags($item->content), 0, 50 ) ."..." !!}</td>
    <td>{{$item -> course -> name}}</td>
    
    @if($isAdmin || $isTeacher)
    <td>
    <a href="/admin/lesson/edit/{{$item -> id}}">Szerkesztés</a>
    <a href="/admin/lesson/delete/{{$item -> id}}">Törlés</a>
    </td>
    <td><a href="/course/{{$course_id}}/lesson/content/{{$item -> id}}">Teljes Tananyag</a></td>
    @else
    <td><a href="/course/{{$course_id}}/lesson/content/{{$item -> id}}">Teljes Tananyag</a></td>
    @endif
  </tr>  
  @endif
  @endforeach

</tbody>
</table>

@include('layout.footer')
