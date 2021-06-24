/**
 * shifts-add.component.ts
 * 
 *  Main typescript class for the shifts-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-24 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-shifts-add',
  templateUrl: './shifts-add.component.html',
  styleUrls: ['./shifts-add.component.scss']
})
export class ShiftsAddComponent implements OnInit {

  title = ''

  constructor(private translate: TranslateService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.ShiftsAdd;
    });
  }
}
