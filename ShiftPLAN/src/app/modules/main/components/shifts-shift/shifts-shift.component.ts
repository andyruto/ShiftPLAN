/**
 * shifts-shift.component.ts
 * 
 *  Main typescript class for the shifts-shift component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-24 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-shifts-shift',
  templateUrl: './shifts-shift.component.html',
  styleUrls: ['./shifts-shift.component.scss']
})
export class ShiftsShiftComponent implements OnInit {

  title = ''

  constructor(private translate: TranslateService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.title = translation.Toolbar.Title.ShiftsShift;
    });
  }
}
