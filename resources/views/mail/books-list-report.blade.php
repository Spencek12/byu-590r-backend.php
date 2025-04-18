Here's a list of all the books:

<table>
   <thead>
       <tr>
           <th>Book Name</th>
           <th>Book Description</th>
       </tr>
   </thead>
   <tbody>
       @foreach ($books as $book)
       <tr>
           <td>{{ $book->name }}</td>
           <td>{{ $book->description }}</td>
       </tr>   
       @endforeach
     
   </tbody>
</table>