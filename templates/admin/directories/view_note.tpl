{* Smarty *}
<div id="table_manage">
  <table>
    <tr>
      <td class="property">Author</td>
      <td>{$note->_author_id|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Summary</td>
      <td>{$note->summary|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Date</td>
      <td>{$note->date}</td>
    </tr>
    <tr>
      <td class="property">Authorization</td>
      <td>{$note->auth|escape:htmlall}</td>
    </tr>
    <tr>
      <td class="property">Contents</td>
      <td>{$note->display()}</td>
    </tr>
  </table>
</div>