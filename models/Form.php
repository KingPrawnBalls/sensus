<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "form".
 *
 * @property int $id
 * @property int $year
 * @property string $name
 * @property string $status
 *
 * @property Attendance[] $attendances
 * @property StudentForm[] $studentForms
 * @property Student[] $students
 */
class Form extends \yii\db\ActiveRecord
{

    const STATUS_DELETED = 'D';
    const STATUS_INACTIVE = 'I';
    const STATUS_ACTIVE = 'A';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'name', 'status'], 'required'],
            [['year'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'name' => 'Name',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttendances()
    {
        return $this->hasMany(Attendance::className(), ['form_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentForms()
    {
        return $this->hasMany(StudentForm::className(), ['form_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['id' => 'student_id'])->viaTable('student_form', ['form_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return FormQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FormQuery(get_called_class());
    }
}
