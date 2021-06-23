/**
 * settings-info.component.spec
 * 
 * Typescript class containing the tests for the 
 * settings-info component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-07 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { SettingsInfoComponent } from './settings-info.component';

describe('SettingsInfoComponent', () => {
  let component: SettingsInfoComponent;
  let fixture: ComponentFixture<SettingsInfoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SettingsInfoComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SettingsInfoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
