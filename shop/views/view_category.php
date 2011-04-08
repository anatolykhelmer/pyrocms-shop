<table>
    <tr>
        <td>Image</td>
        <td>Name</td>
        <td>Price</td>
    </tr>
        <?php foreach ($items->result() as $item) {
        echo '<tr><td><img src="' .$thumbs[$item->id]. '" >';
        echo '</td><td>' .$item->name. '</td><td>' .$item->price. ' &#8362;</td></tr>';
    }
    ?>
</table>