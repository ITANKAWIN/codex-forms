 <form>
     <div class="ui stackable menu massive ">
         <div href="#" class="item">
             <img class="logo" src="<?= CODEX_URL ?>assets/image/codex-plugin_logo.png">
         </div>
         <div class="item header">
             Codex-Forms
         </div>
     </div>
     <div class="ui form">
         <div class="inline fields">
             <div class="twelve wide field">
                 <a href="#">My Forms</a>|<a href="#">Trashed</a>
             </div>
             <div class="four wide field">
                 <input type="text" name="search" id="">
                 <button class="Medium ui primary basic button">Search</button>
             </div>
         </div>
     </div>
     <div class="ui form">
         <div class="inline fields">
             <div class="four wide field">
                 <select class="ui dropdown" name="actions" id="">
                     <option>Bulk actions</option>
                     <option>Move to Trash</option>
                     <option>Export</option>
                 </select>
                 <button class="Medium ui primary basic button">Apply</button>
             </div>
             <div class="two wide field">
                 <input type="text" class="ui" placeholder="Filter Forms">
             </div>
             <div class="two wide field">
                 <input type="text" placeholder="Begin Date">
             </div>
             <div class="two wide field">
                 <input type="text" placeholder="End Date">
             </div>
             <div class="one wide field">
                 <button class="Medium ui primary basic button">Filter</button>
             </div>
         </div>
     </div>
     <table class="ui table">
         <thead>
             <tr>
                 <th class="one wide"><input type="checkbox"></th>
                 <th class="one wide">ID</th>
                 <th class="six wide">Form Title</th>
                 <th class="one wide">
                     <h5 class="ui center aligned">Entries</h5>
                 </th>
                 <th class="three wide">Shortcodes</th>
                 <th class="two wide">Date</th>
             </tr>
         </thead>
         <tbody>
             <tr>
                 <td><input type="checkbox"></td>
                 <td>1</td>
                 <td>Customer</td>
                 <td>
                     <a href="#">
                         <h5 class="ui center aligned">0</h5>
                     </a>
                 </td>
                 <td>[codex_form_preivew id=2]</td>
                 <td>30:10:2021</td>
             </tr>
             <tr>
                 <td><input type="checkbox"></td>
                 <td>2</td>
                 <td>Product</td>
                 <td>
                     <a href="#">
                         <h5 class="ui center aligned">0</h5>
                     </a>
                 </td>
                 <td>[codex_form_preivew id=3]</td>
                 <td>30:10:2021</td>
             </tr>
         </tbody>
         <tfoot>
             <tr>
                 <th colspan="7">
                     <div class="ui right floated pagination menu">
                         <a class="icon item">
                             <i class="left chevron icon"></i>
                         </a>
                         <a class="item">1</a>
                         <a class="item">2</a>
                         <a class="item">3</a>
                         <a class="icon item">
                             <i class="right chevron icon"></i>
                         </a>
                     </div>
                 </th>
             </tr>
         </tfoot>
     </table>
 </form>