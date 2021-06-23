/**
 * settings-theme.component.spec
 * 
 * Typescript class containing the tests for the 
 * settings-theme component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { SettingsThemeComponent } from './settings-theme.component';

describe('SettingsThemeComponent', () => {
  let component: SettingsThemeComponent;
  let fixture: ComponentFixture<SettingsThemeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SettingsThemeComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SettingsThemeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
